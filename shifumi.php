<?php

session_start();

if (!isset($_SESSION['historique'])) {
    $_SESSION['historique'] = [];
}

function menu_principal() {
    echo "\nMenu Principal:\n";
    echo "1. Nouvelle partie\n";
    echo "2. Consulter l'historique des parties\n";
    echo "3. Consulter les statistiques\n";
    echo "4. Quitter\n";

    echo "Choisissez une option: ";
    $choix = trim(fgets(STDIN));

    switch ($choix) {
        case '1':
            nouvelle_partie();
            break;
        case '2':
            afficher_historique();
            break;
        case '3':
            afficher_statistiques();
            break;
        case '4':
            echo "Au revoir!\n";
            exit;
        default:
            echo "Choix invalide. Veuillez réessayer.\n";
            menu_principal();
    }
}

function nouvelle_partie() {
    echo "Choisissez entre Pierre, Feuille, Ciseau (ou tapez 'retour' pour revenir au menu): ";
    $choix_joueur = strtolower(trim(fgets(STDIN)));
    if ($choix_joueur == 'retour') return;

    $options = ['pierre', 'feuille', 'ciseau'];
    if (!in_array($choix_joueur, $options)) {
        echo "Choix invalide. Veuillez réessayer.\n";
        nouvelle_partie();
        return;
    }

    $choix_cpu = $options[array_rand($options)];
    echo "CPU a choisi: $choix_cpu\n";

    if ($choix_joueur == $choix_cpu) {
        $resultat = 'Égalité';
    } elseif (
        ($choix_joueur == 'pierre' && $choix_cpu == 'ciseau') ||
        ($choix_joueur == 'feuille' && $choix_cpu == 'pierre') ||
        ($choix_joueur == 'ciseau' && $choix_cpu == 'feuille')
    ) {
        $resultat = 'Victoire';
    } else {
        $resultat = 'Défaite';
    }

    echo "Résultat: $resultat\n";

    $_SESSION['historique'][] = [
        'date' => date('Y-m-d H:i:s'),
        'joueur' => $choix_joueur,
        'cpu' => $choix_cpu,
        'resultat' => $resultat
    ];

    echo "1. Rejouer | 2. Retour au menu principal: ";
    $choix = trim(fgets(STDIN));
    if ($choix == '1') {
        nouvelle_partie();
    }
}

function afficher_historique() {
    echo "Historique des parties:\n";
    echo "Date                | Joueur  | CPU     | Résultat\n";
    echo "--------------------------------------------------\n";

    foreach ($_SESSION['historique'] as $partie) {
        echo "{$partie['date']} | {$partie['joueur']} | {$partie['cpu']} | {$partie['resultat']}\n";
    }

    echo "Appuyez sur Entrée pour revenir au menu principal.";
    trim(fgets(STDIN));
}

function afficher_statistiques() {
    $total_parties = count($_SESSION['historique']);
    $victoires = count(array_filter($_SESSION['historique'], fn($p) => $p['resultat'] === 'Victoire'));
    $defaites = count(array_filter($_SESSION['historique'], fn($p) => $p['resultat'] === 'Défaite'));
    $egalites = count(array_filter($_SESSION['historique'], fn($p) => $p['resultat'] === 'Égalité'));
    $taux_victoire = $total_parties > 0 ? ($victoires / $total_parties) * 100 : 0;

    echo "Statistiques des parties:\n";
    echo "Nombre de parties jouées: $total_parties\n";
    echo "Taux de victoire: " . number_format($taux_victoire, 2) . "%\n";
    echo "Victoires: $victoires\n";
    echo "Défaites: $defaites\n";
    echo "Égalités: $egalites\n";

    echo "Appuyez sur Entrée pour revenir au menu principal.";
    trim(fgets(STDIN));
}

menu_principal();

?>
