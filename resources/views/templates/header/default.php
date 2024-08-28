<header id="main-header" class="bg-white z-[1000] w-full top-0 fixed transition-shadow duration-300 ease-in-out">
  <div class="custom-container-width mx-auto py-4 px-4 md:py-0 md:px-0">
    <div class="flex h-10 md:h-20 items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="/" aria-label="Go home" title="Company" class="inline-flex items-center">
        <img src="/logo.png" alt="Logo" class="h-6 w-full mr-3 object-contain object-left">
        </a>
      </div>
      
      <!-- Navigation -->
      <nav class="hidden md:block p-4">
                        <ul id="headerMenu" class="flex space-x-4">
                            <!-- Menu items will be dynamically inserted here -->
                        </ul>
                    </nav>
      

                                       <!-- Mobile menu button -->
                                       <div class="md:hidden">
                        <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-gray-500">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="mobile-menu fixed inset-0 bg-white z-50 md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <button id="close-mobile-menu" class="absolute top-3 right-3 text-gray-500 hover:text-gray-600">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <ul id="mobileMenu" class="mt-8">
                        <!-- Mobile menu items will be dynamically inserted here -->
                    </ul>

    </div>
  </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const header = document.getElementById('main-header');
    const scrollThreshold = 50;

    if (header) {  // header 요소가 존재하는지 확인
        window.addEventListener('scroll', function() {
            if (window.scrollY > scrollThreshold) {
                header.classList.add('shadow-[0px_2px_4px_rgba(0,0,0,0.1)]');
            } else {
                header.classList.remove('shadow-[0px_2px_4px_rgba(0,0,0,0.1)]');
            }
        });
    } else {
        console.error('Header element not found');
    }
});
</script>