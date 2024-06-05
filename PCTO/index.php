<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Pre-flight request
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Max-Age: 86400');  // Cache for 1 day
    exit(0);
}

require 'vendor/autoload.php';
require 'db.php';

session_start();

$f3 = Base::instance();
$f3->config('config.ini');
$f3->set('BASE', '/PCTO');


// Funzione per ottenere i dettagli di un'azienda
function getAziendeConVoti($order = null, $studenteId = null, $search = null) {
    $pdo = DB::get();
    $orderBy = "A.nome";  // Ordinamento predefinito per nome azienda
    if ($order === 'ASC' || $order === 'DESC') {
        $orderBy = "COALESCE(AVG(R.voto), 0) $order"; // Ordinamento per voto medio se specificato
    }

    $searchCondition = '';
    $params = [$studenteId];
    if ($search) {
        $searchCondition = "AND A.nome LIKE ?";
        $params[] = '%' . $search . '%';
    }

    $sql = "SELECT A.id, A.nome, A.descrizione, COALESCE(AVG(R.voto), 0) AS voto_medio,
            CASE WHEN P.idStudente IS NULL THEN 0 ELSE 1 END AS isFavorite
            FROM Azienda A
            LEFT JOIN Recensione R ON A.id = R.idAzienda
            LEFT JOIN Preferiti P ON A.id = P.idAzienda AND P.idStudente = ?
            WHERE 1=1 $searchCondition
            GROUP BY A.id
            ORDER BY $orderBy";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Funzione per ottenere i dettagli completi di un'azienda, inclusi i dettagli del tutor e le recensioni
function getDettagliAzienda($id) {
    $pdo = DB::get();
    $sql = "SELECT A.*, T.nome AS tutor_nome, T.cognome AS tutor_cognome, T.email AS tutor_email
            FROM Azienda A
            LEFT JOIN Tutor T ON T.idAzienda = A.id
            WHERE A.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $azienda = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($azienda) {
        $sql = "SELECT R.*, S.nome AS studente_nome, S.cognome AS studente_cognome
                FROM Recensione R
                JOIN Studente S ON R.idStudente = S.id
                WHERE R.idAzienda = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $azienda['recensioni'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $azienda['tutor'] = [
            'nome' => $azienda['tutor_nome'],
            'cognome' => $azienda['tutor_cognome'],
            'email' => $azienda['tutor_email']
        ];
    }
    return $azienda;
}


// Route della Home
$f3->route('GET /', function($f3) {
    $order = $f3->get('GET.order') ?? 'ALPHA';
    $search = $f3->get('GET.search');  // Retrieve the search term from query string
    $aziende = getAziendeConVoti($order, $search);
    $f3->set('aziende', $aziende);
    $f3->set('search', $search);  // Pass the search term back to the template for display in the search bar

    echo Template::instance()->render('home.php');
});

// Route per i dettagli di un'azienda specifica
$f3->route('GET /azienda/@id', function($f3, $params) {
    $id = $params['id'];
    $azienda = getDettagliAzienda($id);
    if ($azienda) {
        $f3->mset([
            'nome' => $azienda['nome'],
            'indirizzo' => $azienda['indirizzo'],
            'descrizione' => $azienda['descrizione'],
            'tutor_nome' => $azienda['tutor']['nome'],
            'tutor_cognome' => $azienda['tutor']['cognome'],
            'tutor_email' => $azienda['tutor']['email'],
            'recensioni' => $azienda['recensioni']
        ]);

        // Renderizza il template HTML
        echo Template::instance()->render('dettagli_azienda.php');
    } else {
        $f3->error(404, 'Azienda non trovata');
    }
});



// ACCESSO
// Route login
$f3->route('POST /login', function ($f3) {
    $db = DB::get();
    $email = $f3->get('POST.email');
    $password = $f3->get('POST.password');
    $role = $f3->get('POST.role');
    $table = $role === 'admin' ? 'Amministratore' : 'Studente';

    $stmt = $db->prepare("SELECT * FROM $table WHERE email = :email AND password = :password");
    $stmt->execute([':email' => $email, ':password' => $password]);
    $result = $stmt->fetch();

    if ($result) {
        $_SESSION['user'] = $result;
        $_SESSION['user']['role'] = $role;  // Assicurati di impostare anche il ruolo
        $_SESSION['studente_nome'] = $result['nome']; 
        $_SESSION['studente_cognome'] = $result['cognome'];
    
        if ($role === 'admin') {
            $f3->reroute('/amministratore');
        } else {
            $f3->reroute('/studente');
        }
    } else {
        $f3->reroute('/login/error?error=login');
    }
});    

// Route login errore
$f3->route('GET /login/error', function($f3) {
    $error = $f3->get('GET.error') ?? null;
    echo Template::instance()->render('login.php');
});

// Route logout
$f3->route('GET /logout', function($f3) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_destroy();
    $f3->reroute('/');
});


// STUDENTE
// Route che porta alla pagina di benvenuto dello Studente
$f3->route('GET /studente', function($f3) {
    session_start();

    if (!isset($_SESSION['studente_nome'], $_SESSION['studente_cognome'])) {
        $f3->reroute('/login');
        exit;
    }

    $studente_nome = htmlspecialchars($_SESSION['studente_nome']);
    $studente_cognome = htmlspecialchars($_SESSION['studente_cognome']);
    $welcomeMessage = "Bentornato Studente, $studente_nome $studente_cognome!";
    $f3->set('welcomeMessage', $welcomeMessage);

    echo Template::instance()->render('studente.php');
});


// Route che porta alla pagina principale dello Studente
$f3->route('GET /aziende', function($f3) {
    $order = $f3->get('GET.order') ?? 'ALPHA';
    $search = $f3->get('GET.search');
    $studenteId = $_SESSION['user']['id'];

    $aziende = getAziendeConVoti($order, $studenteId, $search);
    $f3->set('aziende', $aziende);
    $f3->set('search', $search);  // Pass the search term back to the template

    $studente_nome = htmlspecialchars($_SESSION['studente_nome']);
    $studente_cognome = htmlspecialchars($_SESSION['studente_cognome']);
    $message = "$studente_nome $studente_cognome";
    $f3->set('message', $message);

    echo Template::instance()->render('aziende.php');
});


// Route per aggiungere/rimuovere un'azienda dai preferiti
$f3->route('POST /toggle_favorite', function($f3) {
    $aziendaId = $f3->get('GET.aziendaId');
    $studenteId = $_SESSION['user']['id'];

    $db = DB::get();
    $stmt = $db->prepare("SELECT * FROM Preferiti WHERE idStudente = ?");
    $stmt->execute([$studenteId]);
    $preferiti = $stmt->fetchAll();

    // Verifica se l'azienda è già marcata come preferita
    $stmt = $db->prepare("SELECT * FROM Preferiti WHERE idStudente = ? AND idAzienda = ?");
    $stmt->execute([$studenteId, $aziendaId]);
    if ($stmt->fetch()) {
        // Rimuovi dai preferiti
        $stmt = $db->prepare("DELETE FROM Preferiti WHERE idStudente = ? AND idAzienda = ?");
        $success = $stmt->execute([$studenteId, $aziendaId]);
        echo json_encode(['success' => $success, 'removed' => true]);
    } else {
        // Limita a 3 preferiti
        if (count($preferiti) >= 3) {
            echo json_encode(['success' => false, 'error' => 'Puoi avere solo 3 aziende preferite.']);
            return;
        }
        // Aggiungi ai preferiti
        $stmt = $db->prepare("INSERT INTO Preferiti (idStudente, idAzienda) VALUES (?, ?)");
        $success = $stmt->execute([$studenteId, $aziendaId]);
        echo json_encode(['success' => $success, 'added' => true]);
    }
});

// Route che mostra i preferiti dello studente
$f3->route('GET /preferiti', function($f3) {
    $db = DB::get(); 
    $studenteId = $_SESSION['user']['id']; 

    $sql = "SELECT A.id, A.nome, A.descrizione, COALESCE(AVG(R.voto), 0) AS voto_medio
            FROM Azienda A
            JOIN Preferiti P ON A.id = P.idAzienda
            LEFT JOIN Recensione R ON A.id = R.idAzienda
            WHERE P.idStudente = ?
            GROUP BY A.id
            ORDER BY A.nome";
    $stmt = $db->prepare($sql);
    $stmt->execute([$studenteId]);
    $preferiti = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $f3->set('preferiti', $preferiti);
    echo Template::instance()->render('preferiti.php');
});


// Route per andare alla pagina del form del diario di bordo da compilare
$f3->route('GET /diario_di_bordo', function($f3) {
    session_start();
    echo Template::instance()->render('diario_di_bordo.php');
});


// Route di invio del diario di bordo
$f3->route('POST /diario_di_bordo-invio', function($f3) {
    session_start();
    $db = DB::get();
    $giorno = $f3->get('POST.giorno');
    $entrataMattina = $f3->get('POST.entrataMattina');
    $uscitaMattina = $f3->get('POST.uscitaMattina');
    $entrataPome = $f3->get('POST.entrataPome');
    $uscitaPome = $f3->get('POST.uscitaPome');
    $descrizione = $f3->get('POST.descrizione');
    $studenteId = $_SESSION['user']['id'];

    $sql = "INSERT INTO DiarioDiBordo (giorno, entrataMattina, uscitaMattina, entrataPome, uscitaPome, descrizione, idStudente) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if ($stmt->execute([$giorno, $entrataMattina, $uscitaMattina, $entrataPome, $uscitaPome, $descrizione, $studenteId])) {
        $f3->reroute('/diario_di_bordo?success=1');  // Reindirizza con parametro di successo
    } else {
        $f3->reroute('/diario_di_bordo?error=1');
    }    
});



// Route che fa vedere tutti i diari di bordo compilati da un determinato utente loggato
$f3->route('GET /diario_di_bordo/visualizza', function($f3) {
    session_start();
    
    // Verifica se l'utente è loggato e ha un id valido.
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        $f3->reroute('/login');
        exit;
    }

    $db = DB::get();
    $studenteId = $_SESSION['user']['id'];

    // Prepara e esegui la query per ottenere i diari di bordo dello studente
    $sql = "SELECT * FROM DiarioDiBordo WHERE idStudente = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$studenteId]);
    $diari = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $f3->set('diari', $diari);

    echo Template::instance()->render('visualizza_diario_di_bordo.php');
});

// Route per visualizzare il form del diario di bordo da modificare
$f3->route('GET /diario_di_bordo/modifica/@id', function($f3, $params) {
    session_start();
    
    if (!isset($_SESSION['user']) || !$_SESSION['user']['id']) {
        $f3->reroute('/login');
        exit;
    }

    $db = DB::get();
    $stmt = $db->prepare("SELECT * FROM DiarioDiBordo WHERE id = ?");
    $stmt->execute([$params['id']]);
    $diario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$diario || $diario['idStudente'] != $_SESSION['user']['id']) {
        $f3->reroute('/diario_di_bordo/visualizza');
        exit;
    }

    $f3->set('diario', $diario);
    echo Template::instance()->render('modifica_diario_di_bordo.php');
});

// Route per il form del diario di bordo da modificare
$f3->route('POST /diario_di_bordo/modifica/@id', function($f3, $params) {
    session_start();

    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        $f3->reroute('/login');
        exit;
    }

    $db = DB::get();
    $studenteId = $_SESSION['user']['id'];
    $diarioId = $params['id'];

    $giorno = $f3->get('POST.giorno');
    $entrataMattina = $f3->get('POST.entrataMattina');
    $uscitaMattina = $f3->get('POST.uscitaMattina');
    $entrataPome = $f3->get('POST.entrataPome');
    $uscitaPome = $f3->get('POST.uscitaPome');
    $descrizione = $f3->get('POST.descrizione');

    $sql = "UPDATE DiarioDiBordo SET giorno = ?, entrataMattina = ?, uscitaMattina = ?, entrataPome = ?, uscitaPome = ?, descrizione = ? WHERE id = ? AND idStudente = ?";
    if ($stmt = $db->prepare($sql) and $stmt->execute([$giorno, $entrataMattina, $uscitaMattina, $entrataPome, $uscitaPome, $descrizione, $diarioId, $studenteId])) {
        $f3->reroute('/diario_di_bordo/visualizza?success=1');
    } else {
        $f3->reroute('/diario_di_bordo/modifica/' . $diarioId . '?error=1');
    }
});



// visualizzazione della recensione
$f3->route('GET /recensione', function($f3) {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'studente') {
        $f3->reroute('/login');
        exit;
    }

    $studenteId = $_SESSION['user']['id'];

    // Retrieve all reviews for the student
    $db = DB::get();
    $stmt = $db->prepare("SELECT R.*, A.nome AS azienda_nome, A.id AS azienda_id 
                          FROM Recensione R
                          JOIN Azienda A ON R.idAzienda = A.id
                          WHERE R.idStudente = ?");
    $stmt->execute([$studenteId]);
    $recensioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retrieve the companies the student is assigned to but hasn't reviewed yet
    $stmt = $db->prepare("SELECT A.id, A.nome 
                          FROM Assegnazione ASG
                          JOIN Azienda A ON ASG.idAzienda = A.id
                          LEFT JOIN Recensione R ON R.idAzienda = A.id AND R.idStudente = ASG.idStudente
                          WHERE ASG.idStudente = ? AND R.id IS NULL");
    $stmt->execute([$studenteId]);
    $aziende_non_recensite = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($recensioni) && empty($aziende_non_recensite)) {
        $f3->set('no_aziende', true);
    } else {
        $f3->set('no_aziende', false);
    }

    $f3->set('recensioni', $recensioni);
    $f3->set('aziende_non_recensite', $aziende_non_recensite);
    echo Template::instance()->render('recensione.php');
});


// visualizzare modulo di modifica della recensione
$f3->route('GET /recensione/modifica/@idAzienda', function($f3, $params) {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'studente') {
        $f3->reroute('/login');
        exit;
    }

    $aziendaId = $params['idAzienda'];
    $studenteId = $_SESSION['user']['id'];

    // Retrieve company information
    $db = DB::get();
    $stmt = $db->prepare("SELECT * FROM Azienda WHERE id = ?");
    $stmt->execute([$aziendaId]);
    $azienda = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$azienda) {
        echo "Azienda non trovata";
        return;
    }

    // Retrieve the student's review for this company
    $stmt = $db->prepare("SELECT * FROM Recensione WHERE idStudente = ? AND idAzienda = ?");
    $stmt->execute([$studenteId, $aziendaId]);
    $recensione = $stmt->fetch(PDO::FETCH_ASSOC);

    $f3->set('azienda', $azienda);
    $f3->set('recensione', $recensione);
    echo Template::instance()->render('recensione_modifica.php');
});



// visualizzare modulo di creazione della recensione
$f3->route('GET /recensione/crea/@idAzienda', function($f3, $params) {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'studente') {
        $f3->reroute('/login');
        exit;
    }

    $aziendaId = $params['idAzienda'];
    $studenteId = $_SESSION['user']['id'];

    // Recupera informazioni sull'azienda
    $db = DB::get();
    $stmt = $db->prepare("SELECT * FROM Azienda WHERE id = ?");
    $stmt->execute([$aziendaId]);
    $azienda = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$azienda) {
        echo "Azienda non trovata";
        return;
    }

    $f3->set('azienda', $azienda);
    echo Template::instance()->render('recensione_crea.php');
});

// modifica o creazione della recensione
$f3->route('POST /recensione/invio', function($f3) {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'studente') {
        $f3->reroute('/login');
        exit;
    }

    $db = DB::get();
    $studenteId = $_SESSION['user']['id'];
    $aziendaId = $f3->get('POST.idAzienda');
    $voto = $f3->get('POST.voto');
    $commento = $f3->get('POST.commento');

    // Controlla se esiste già una recensione
    $stmt = $db->prepare("SELECT * FROM Recensione WHERE idStudente = ? AND idAzienda = ?");
    $stmt->execute([$studenteId, $aziendaId]);
    $recensione = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recensione) {
        // Aggiorna la recensione esistente
        $sql = "UPDATE Recensione SET voto = ?, commento = ? WHERE idStudente = ? AND idAzienda = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$voto, $commento, $studenteId, $aziendaId]);
    } else {
        // Inserisci una nuova recensione
        $sql = "INSERT INTO Recensione (voto, commento, idStudente, idAzienda) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$voto, $commento, $studenteId, $aziendaId]);
    }

    $f3->reroute('/recensione');
});




// AMMINISTRATORE
// Route che porta alla pagina di benvenuto dell Amministratore
$f3->route('GET /amministratore', function($f3) {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $f3->reroute('/login');
        exit;
    }
    $admin_nome = htmlspecialchars($_SESSION['user']['nome']);
    $admin_cognome = htmlspecialchars($_SESSION['user']['cognome']);
    $welcomeMessage = "Benvenuto amministratore, $admin_nome $admin_cognome!";
    $f3->set('welcomeMessage', $welcomeMessage);

    echo Template::instance()->render('amministratore.php');
});

// Route che porta alla pagina di gestione dell'Amministratore
$f3->route('GET /gestione_amministratore', function($f3) {
    $order = $f3->get('GET.order') ?? 'ALPHA';
    $search = $f3->get('GET.search');
    $studenteId = $_SESSION['user']['id'];

    $aziende = getAziendeConVoti($order, $studenteId, $search);
    $f3->set('aziende', $aziende);
    $f3->set('search', $search); 

    $admin_nome = htmlspecialchars($_SESSION['user']['nome']);
    $admin_cognome = htmlspecialchars($_SESSION['user']['cognome']);
    $welcomeMessage = "$admin_nome $admin_cognome";
    $f3->set('welcomeMessage', $welcomeMessage);

    echo Template::instance()->render('aziende_amministratore.php');
});

// Route che porta alla pagina degli studenti con tutti i loro dettagli
$f3->route('GET /amministratore/studenti', function($f3) {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $f3->reroute('/login');
        exit;
    }

    $db = DB::get();
    // Retrieve search parameters
    $nameSearch = $f3->get('GET.nome');
    $surnameSearch = $f3->get('GET.cognome');
    $classSearch = $f3->get('GET.classe');
    $companySearch = $f3->get('GET.azienda_attuale');

    // Base SQL query
    $studentQuery = "SELECT Studente.id, Studente.nome, Studente.cognome, Studente.email, Studente.classe, Azienda.nome AS azienda_attuale
                     FROM Studente
                     LEFT JOIN Assegnazione ON Studente.id = Assegnazione.idStudente
                     LEFT JOIN Azienda ON Assegnazione.idAzienda = Azienda.id";

    // Dynamic conditions for SQL query
    $conditions = [];
    $params = [];
    if (!empty($nameSearch)) {
        $conditions[] = "Studente.nome LIKE :nome";
        $params[':nome'] = "%$nameSearch%";
    }
    if (!empty($surnameSearch)) {
        $conditions[] = "Studente.cognome LIKE :cognome";
        $params[':cognome'] = "%$surnameSearch%";
    }
    if (!empty($classSearch)) {
        $conditions[] = "Studente.classe LIKE :classe";
        $params[':classe'] = "%$classSearch%";
    }
    if (!empty($companySearch)) {
        $conditions[] = "Azienda.nome LIKE :azienda_attuale";
        $params[':azienda_attuale'] = "%$companySearch%";
    }

    // Append conditions to the query if they exist
    if (!empty($conditions)) {
        $studentQuery .= " WHERE " . implode(' AND ', $conditions);
    }

    $studentQuery .= " ORDER BY Studente.classe ASC";

    $stmt = $db->prepare($studentQuery);
    $stmt->execute($params);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare preferences query
    $preferencesQuery = "SELECT Studente.id AS student_id, GROUP_CONCAT(Azienda.nome SEPARATOR ', ') AS aziende_preferite
                         FROM Studente
                         LEFT JOIN Preferiti ON Studente.id = Preferiti.idStudente
                         LEFT JOIN Azienda ON Preferiti.idAzienda = Azienda.id
                         GROUP BY Studente.id
                         ORDER BY Studente.id";
    $preferences = $db->query($preferencesQuery)->fetchAll(PDO::FETCH_ASSOC);

    // Map preferences for easy access
    $preferencesMap = [];
    foreach ($preferences as $pref) {
        $preferencesMap[$pref['student_id']] = $pref['aziende_preferite'];
    }

    // Attach preferences to each student
    foreach ($students as $key => $student) {
        $students[$key]['aziende_preferite'] = $preferencesMap[$student['id']] ?? '';
    }

    $f3->set('studenti', $students);
    $f3->set('nome', $nameSearch);
    $f3->set('cognome', $surnameSearch);
    $f3->set('classe', $classSearch);
    $f3->set('azienda_attuale', $companySearch);
    echo Template::instance()->render('studenti_amministratore.php');
});

// route pagina di modifica dello studente
$f3->route('GET /amministratore/studenti/edit/@id', function($f3, $params) {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $f3->reroute('/login');
        exit;
    }

    // Retrieve success or error messages from the session
    $f3->set('success', $f3->get('SESSION.success') ?: '');
    $f3->set('error', $f3->get('SESSION.error') ?: '');
    $f3->clear('SESSION.success');
    $f3->clear('SESSION.error');

    $db = DB::get();

    // Fetch student details
    $stmt = $db->prepare("SELECT Studente.*, Azienda.nome AS azienda_attuale, Azienda.cap AS azienda_cap FROM Studente
                          LEFT JOIN Assegnazione ON Studente.id = Assegnazione.idStudente
                          LEFT JOIN Azienda ON Assegnazione.idAzienda = Azienda.id
                          WHERE Studente.id = ?");
    $stmt->execute([$params['id']]);
    $studente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$studente) {
        $f3->set('error', "Studente non trovato");
        $f3->reroute('/amministratore/studenti');
        return;
    }

    // Fetch all related companies
    $aziendeStmt = $db->prepare("SELECT A.id, A.nome, A.cap, IF(P.idAzienda IS NOT NULL, 1, 0) AS preferita,
                                 (SELECT COUNT(*) FROM Assegnazione WHERE idAzienda = A.id) AS studenti_assegnati
                                 FROM Azienda A
                                 LEFT JOIN Preferiti P ON A.id = P.idAzienda AND P.idStudente = ?");
    $aziendeStmt->execute([$params['id']]);
    $aziende = $aziendeStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($aziende as &$azienda) {
    $azienda['class'] = '';
    // Convert both to integers if they are not already
    $aziendaCap = intval($azienda['cap']);
    $studenteCap = intval($studente['cap']);

    if ($azienda['studenti_assegnati'] < 2) {
        if ($aziendaCap == $studenteCap) {
            $azienda['class'] = 'azienda-verde';
        } elseif (abs($aziendaCap - $studenteCap) == 1) {
            $azienda['class'] = 'azienda-giallo';
        }
    } else {
        $azienda['class'] = 'azienda-rosso';
    }
    $azienda['star_class'] = $azienda['preferita'] ? 'fa-star-giallo' : 'fa-star';
}

    

    $f3->set('studente', $studente);
    $f3->set('aziende', $aziende);
    echo Template::instance()->render('modifica_studente.php');
});



// route post di modifica dello studente
$f3->route('POST /amministratore/studenti/update/@id', function($f3, $params) {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $f3->reroute('/login');
        exit;
    }

    $db = DB::get();
    $db->beginTransaction();

    try {
        // Retrieve form data
        $aziendaId = $f3->get('POST.azienda');
        if (!empty($aziendaId)) {
            // Ensure the selected company does not exceed the allowed number of students
            $countStmt = $db->prepare("SELECT COUNT(*) AS count FROM Assegnazione WHERE idAzienda = ?");
            $countStmt->execute([$aziendaId]);
            $count = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];

            if ($count >= 2) {
                throw new Exception("Questa azienda ha già due studenti assegnati.");
            }
        }

        // Update student details
        $stmt = $db->prepare("UPDATE Studente SET nome = ?, cognome = ?, email = ?, classe = ? WHERE id = ?");
        $stmt->execute([
            $f3->get('POST.nome'),
            $f3->get('POST.cognome'),
            $f3->get('POST.email'),
            $f3->get('POST.classe'),
            $params['id']
        ]);

        // Check if an existing assignment exists
        $checkStmt = $db->prepare("SELECT * FROM Assegnazione WHERE idStudente = ?");
        $checkStmt->execute([$params['id']]);
        $existingAssignment = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingAssignment && $aziendaId) {
            $updateStmt = $db->prepare("UPDATE Assegnazione SET idAzienda = ? WHERE idStudente = ?");
            $updateStmt->execute([$aziendaId, $params['id']]);
        } elseif (!$aziendaId) {
            $deleteStmt = $db->prepare("DELETE FROM Assegnazione WHERE idStudente = ?");
            $deleteStmt->execute([$params['id']]);
        } elseif ($aziendaId && !$existingAssignment) {
            $insertStmt = $db->prepare("INSERT INTO Assegnazione (idStudente, idAzienda) VALUES (?, ?)");
            $insertStmt->execute([$params['id'], $aziendaId]);
        }

        $db->commit();
        $f3->set('SESSION.success', "Modifica completata con successo!");
    } catch (Exception $e) {
        $db->rollback();
        $f3->set('SESSION.error', $e->getMessage());
    }

    $f3->reroute('/amministratore/studenti/edit/' . $params['id']);
});


$f3->run();