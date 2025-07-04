</div>
<footer class="bg-white border-t border-gray-200 mt-12">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
                © <?= date('Y') ?> ChatBot BIPA. All rights reserved.
            </div>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-github"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Global Scripts -->
<script>
    // Global utility functions
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function formatNumber(number, decimals = 2) {
        return parseFloat(number).toFixed(decimals);
    }

    function showToast(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-sm ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-white' :
                'bg-blue-500 text-white'
            }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Loading spinner utility
    function showLoading(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `
                    <div class="flex items-center justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        <span class="ml-2 text-gray-500">Loading...</span>
                    </div>
                `;
        }
    }

    // Error handling utility
    function showError(elementId, message = 'An error occurred while loading data') {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `
                    <div class="flex items-center justify-center py-8">
                        <div class="text-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-3xl mb-2"></i>
                            <p class="text-gray-500">${message}</p>
                            <button onclick="location.reload()" class="mt-2 px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700 transition-colors">
                                Try Again
                            </button>
                        </div>
                    </div>
                `;
        }
    }

    // API call utility with error handling
    async function apiCall(url, options = {}) {
        try {
            const response = await fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                },
                ...options
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API call failed:', error);
            showToast('Failed to load data. Please try again.', 'error');
            throw error;
        }
    }

    // Initialize tooltips and other UI enhancements
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading states to buttons
        document.querySelectorAll('button[type="submit"]').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.disabled) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                }
            });
        });

        // Add smooth scrolling to anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
</body>

</html>