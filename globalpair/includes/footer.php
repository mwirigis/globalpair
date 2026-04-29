<?php
/**
 * GlobePair - Footer Template
 * Footer and scripts
 */
?>
    </main>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 GlobePair. Find your perfect match today. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Auto-scroll chat to bottom on load
            const msgArea = document.getElementById('messagesArea');
            if(msgArea) {
                msgArea.scrollTop = msgArea.scrollHeight;
            }

            // Photo Upload Preview
            const photoInput = document.getElementById('profilePhotoInput');
            const photoPreview = document.getElementById('photoPreview');
            const photoPlaceholder = document.getElementById('photoPlaceholder');
            const fileNameDiv = document.getElementById('fileName');
            const fileNameText = document.getElementById('fileNameText');
            const removePhotoBtn = document.getElementById('removePhoto');

            if (photoInput) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    
                    if (file) {
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        if (!allowedTypes.includes(file.type)) {
                            alert('Invalid file type. Please select JPG, PNG, GIF, or WebP.');
                            this.value = '';
                            return;
                        }
                        
                        if (file.size > 5 * 1024 * 1024) {
                            alert('File size must be less than 5MB.');
                            this.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            photoPreview.src = event.target.result;
                            photoPreview.style.display = 'block';
                            photoPlaceholder.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                        
                        fileNameText.textContent = file.name;
                        fileNameDiv.style.display = 'block';
                    }
                });

                if (removePhotoBtn) {
                    removePhotoBtn.addEventListener('click', function() {
                        photoInput.value = '';
                        photoPreview.src = '';
                        photoPreview.style.display = 'none';
                        photoPlaceholder.style.display = 'flex';
                        fileNameDiv.style.display = 'none';
                    });
                }
            }

            // Payment option selection
            document.querySelectorAll('.payment-option').forEach(option => {
                option.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON') {
                        document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>