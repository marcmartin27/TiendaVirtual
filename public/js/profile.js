    function toggleProfilePopup() {
        var popup = document.getElementById('profilePopup');
        popup.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        var profilePopup = document.getElementById('profilePopup');
        var profileIcon = document.getElementById('profileIcon');
        if (!profilePopup.contains(event.target) && event.target !== profileIcon) {
            profilePopup.classList.add('hidden');
        }
    });
