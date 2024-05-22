   <div id="overlay" class="dark:bg-black hidden; z-index:calc(var(--backdrop-z-index) + 6)">
       <img src="{{ asset('svg/logo.svg') }}" alt=""/>
       <div class="spinner"></div>

       <noscript>
           <style>
               #overlay img,
               #overlay .spinner {
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
