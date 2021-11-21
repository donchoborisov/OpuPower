   {{-- header --}}
   <div class="bg-white shadow-md">
    <header class="container mx-auto max-w-7xl flex flex-wrap items-center p-6 justify-between">
        <div class="flex items-center text-sec hover:text-pri cursor-pointer transition">
           
          <span class="font-title text-pri text-3xl">OPU</span> <span class="font-title text-sec text-3xl">POWER</span>
        </div>
        <div class="md:hidden block">
              <button id="menu-open" class=" px-3 py-1 rounded bg-sec text-white hover:bg-purple-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
              </button>
              <button id="menu-close" class="hidden px-3 py-1 rounded bg-sec text-white hover:bg-purple-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
              </button>
        </div>
        <nav id="menu-items" class="hidden md:flex md:items-center font-title w-full md:w-auto">
            <ul class="text-lg">
                <li class="block mb-3 md:my-0 md:inline-block items-center mr-4">
                   <a href="{{route('home')}}" class="text-pri hover:text-sec transition">Home</a>
                </li>
                <li class="block mb-3 md:my-0 md:inline-block items-center mr-4">
                    <a href="#" class="text-pri hover:text-sec transition">About us</a>
                 </li>
                 <li class="block mb-3 md:my-0 md:inline-block items-center mr-4 ">
                  <button class="md:animate-pulse text-sec bg-our-bg hover:bg-blue-200 rounded-lg  px-4 py-2.5 text-center inline-flex items-center" type="button" data-dropdown-toggle="dropdown">Our Services <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                  <!-- Dropdown menu -->
                  <div class="hidden bg-white text-base z-50 list-none divide-y divide-gray-100 rounded shadow my-4" id="dropdown">
                      <div class="px-4 py-3">
                        <span class="block text-sm">What we can do for you</span>
                    
                      </div>
                      <ul class="py-1" aria-labelledby="dropdown">
                        <li>
                          <a href="{{route('page.support')}}" class="text-sm hover:bg-gray-100 text-gray-700 block px-4 py-2">IT Support</a>
                        </li>
                        <li>
                          <a href="{{route('page.maintenance')}}" class="text-sm hover:bg-gray-100 text-gray-700 block px-4 py-2">Network Maintance</a>
                        </li>
                        <li>
                          <a href="{{route('page.install')}}" class="text-sm hover:bg-gray-100 text-gray-700 block px-4 py-2">Network Installation</a>
                        </li>
                        <li>
                          <a href="{{route('page.phone')}}" class="text-sm hover:bg-gray-100 text-gray-700 block px-4 py-2">Telephone Systems</a>
                        </li>
                        <li>
                          <a href="{{route('page.cloud')}}" class="text-sm hover:bg-gray-100 text-gray-700 block px-4 py-2">Cloud Solutions</a>
                        </li>
                        <li>
                          <a href="{{route('page.cctv')}}" class="text-sm hover:bg-gray-100 text-gray-700 block px-4 py-2">CCTV Installation</a>
                        </li>
                      </ul>
                  </div>
                 </li>
                 <li class="block mb-3 md:my-0 md:inline-block items-center mr-4">
                    <a href="{{route('page.contact')}}" class="text-pri hover:text-sec transition">Contact Us</a>
                 </li>
            </ul>
        </nav>

    </header>
</div>

{{-- header end --}}