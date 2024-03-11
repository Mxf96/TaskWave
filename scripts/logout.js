function confirmLogout() {
    let logout = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
    if (logout) {
        // Utilisation d'un chemin absolu
        window.location.href = "/logs/logout.php";
    }
}