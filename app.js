document.addEventListener('DOMContentLoaded', function() {
    var loadPostButtons = document.querySelectorAll('.load-post-btn');
    loadPostButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var postId = this.getAttribute('data-post-id');
            var postContainer = this.parentElement.querySelector('.post-container');
            var commentsContainer = this.parentElement.querySelector('.comments-container');

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        var postBody = response.postBody;
                        var comments = response.comments;
            
                        postContainer.innerHTML = '<p>' + postBody + '</p>';
                        postContainer.style.display = 'block';
            
                        commentsContainer.innerHTML = '<h2>Commentaires</h2>';
                        comments.forEach(function(comment) {
                            commentsContainer.innerHTML += '<div class="comment">' +
                                '<p><strong>' + comment.name + '</strong>: ' + comment.body + '</p>' +
                                '</div>';
                        });
                        commentsContainer.style.display = 'block';
            
                        button.style.display = 'none';
                    } else {
                        alert('Une erreur s\'est produite lors du chargement du post et des commentaires.');
                    }
                }
            };
            xhr.open('GET', 'load_post.php?id=' + postId, true);
            xhr.send();
        });
    });
});
