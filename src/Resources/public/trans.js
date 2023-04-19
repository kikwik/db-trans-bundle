document.addEventListener("DOMContentLoaded", function() {
    let transModal = document.querySelector('.js-trans-modal');
    let transModalContent = transModal.querySelector('.js-trans-modal-content');

    window.onclick = function(event) {
        if (event.target == transModal) {
            transModal.style.display = "none";
        }
    }


    document.addEventListener('click', function (event) {
        if (!event.target.matches('.js-trans-message-edit')) return;

        event.preventDefault();
        transModalContent.innerHTML = 'loading...';
        let url = event.target.dataset.url;
        fetch(url)
            .then(function (response) {
                return response.text();
            })
            .then(function (html){
                transModalContent.innerHTML = html;
                let form = transModalContent.querySelector('form');
                if(form)
                {
                    form.addEventListener('submit',function (event){
                        event.preventDefault();
                        transModalContent.innerHTML = '<i class="fas fa-spin fa-spinner"></i> saving...';
                        fetch(form.action,{
                            method: form.method,
                            body: new FormData(form)
                        })
                            .then(function (response){
                                return response.text();
                            })
                            .then(function (html){
                                transModalContent.innerHTML = html;
                                if(html == 'OK')
                                {
                                    window.location.reload();
                                    transModal.style.display = "none";
                                }
                            })
                            .catch(function (error){
                                transModalContent.innerHTML = 'Post error: '+error;
                            })
                    });
                }
            })
            .catch(function (error) {
                // There was an error
                transModalContent.innerHTML = 'Load error: '+error;
            })
            .finally(function (){
                transModal.style.display = "block";
            });
    }, false);
});


