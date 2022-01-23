app = {
    init: function () {
        const deleteBtns = document.querySelectorAll('.delete');
        for (deleteBtn of deleteBtns) {
            deleteBtn.addEventListener('click', app.handleDelete)
        }
    },

    handleDelete: (e) => {
        if (!window.confirm('Suppression article : En êtes vous sure Mme chaussure ?')) e.preventDefault();
    }
}
document.addEventListener('DOMContentLoaded', app.init);