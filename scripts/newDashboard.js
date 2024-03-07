document.addEventListener('DOMContentLoaded', function () {
    let createTableauLink = document.getElementById('creerTableau');
    let formContainer = document.getElementById('formContainer');
    let modalBackdrop = document.createElement('div');
    modalBackdrop.className = 'modal-backdrop';

    document.body.appendChild(modalBackdrop); // Ajoute le backdrop au corps de la page

    if (createTableauLink && formContainer) {
        createTableauLink.addEventListener('click', function(event) {
            event.preventDefault();
            formContainer.style.display = 'block';
            modalBackdrop.style.display = 'block'; // Affiche le fond grisé
        });

        // Optionnel : Ajoutez un moyen de fermer le formulaire et de masquer le backdrop, par exemple en cliquant sur le backdrop lui-même
        modalBackdrop.addEventListener('click', function() {
            formContainer.style.display = 'none';
            modalBackdrop.style.display = 'none'; // Masque le fond grisé
        });
    }
});
