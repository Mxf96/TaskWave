function confirmLogout() {
    let logout = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
    if (logout) {
        window.location.href = "../logs/logout.php"; // Assurez-vous que le chemin est correct
    }
}