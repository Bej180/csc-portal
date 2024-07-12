   <div id="overlay" class="dark:bg-black hidden; z-index:calc(var(--backdrop-z-index) + 6)">
       <img src="{{ asset('svg/logo.svg') }}" alt=""/>
       <div class="spinner"></div>

       <div class="loading-indicator flex items-center gap-1 absolute bottom-[50px]">
            <div class="dot-pulse"></div>
            <div id="loadingText" class="lg:text-2xl ml-[20px]">
                Loading...
            </div>
        </div>

       <noscript>
        
           <style>
               #overlay img,
               #overlay .spinner,
               #overlay .loading-indicator {
                   display: none
               }

               #overlay {
                   background-color: rgb(247, 250, 252);
               }
           </style>

           <span class="uppercase text-gray-500 tracking-wider text-lg">
               You need to enable your javascript to access this site
           </span>
       </noscript>
   </div>
