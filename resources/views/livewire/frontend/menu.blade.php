 <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
     <h1 class="text-lg font-bold">Tabla de posiciones</h1>

     <!--Nav para móvil (se muestra hasta md)  -->
     <nav class="flex md:hidden space-x-4">

     </nav>

     <!--Nav para escritorio (md en adelante)  -->
     <nav class="hidden md:flex space-x-4">

     </nav>
 </div>


 <!--PAGINA DE INICIO -->
 <div>
     <x-layouts.app.frontend :title="__('Inicio')">
         <section class="bg-white dark:bg-gray-900">
             <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12">
                 <a href="{{route('frontend.fixture.index')}}" class="inline-flex justify-between items-center py-1 px-1 pe-4 mb-7 text-sm text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700">
                     <span class="text-xs bg-blue-600 rounded-full text-white px-4 py-1.5 me-3">¡Nuevo!</span> <span class="text-sm font-medium">Conoce nuestra temporada</span>
                     <svg class="w-2.5 h-2.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                     </svg>
                 </a>
                 <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Vive la pasión del fútbol como nunca antes</h1>
                 <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">Desde partidos amistosos hasta torneos de alto nivel, tenemos el lugar perfecto para tu equipo.</p>
                 <div class="flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                     <a href="#unete" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                         Únete a la comunidad
                         <svg class="ms-2 -me-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                             <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                         </svg>
                     </a>

                 </div>
             </div>
         </section>

         <section id="sobre-nosotros" class="bg-white dark:bg-gray-900">
             <div class="gap-16 items-center py-8 px-4 mx-auto max-w-screen-xl lg:grid lg:grid-cols-2 lg:py-16 lg:px-6">
                 <div class="font-light text-gray-500 sm:text-lg dark:text-gray-400">
                     <h2 class="mb-4 text-4xl font-extrabold text-gray-900 dark:text-white">El mejor lugar para jugar fútbol</h2>
                     <p class="mb-4">Somos un club dedicado a la comunidad, ofreciendo canchas de alta calidad, torneos organizados y un ambiente amistoso para que todos disfruten del deporte. Nuestro objetivo es promover la salud, el trabajo en equipo y la pasión por el fútbol.</p>
                     <p>Contamos con instalaciones de primer nivel y un equipo de staff dedicado a hacer que tu experiencia sea inmejorable.</p>
                 </div>
                 <div class="grid grid-cols-2 gap-4 mt-8">
                     <img class="w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/content/office-long-2.png" alt="cancha de fútbol">
                     <img class="mt-4 w-full lg:mt-10 rounded-lg" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/content/office-long-1.png" alt="equipo de fútbol">
                 </div>
             </div>
         </section>

         <section id="equipos" class="bg-gray-50 dark:bg-gray-800 py-16">
             <div class="max-w-screen-xl mx-auto px-4 text-center">
                 <h2 class="mb-8 text-4xl font-extrabold text-gray-900 dark:text-white">Nuestros Equipos Destacados</h2>
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                     <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 flex flex-col items-center">
                         <img class="w-24 h-24 mb-4 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png" alt="imagen del equipo">
                         <h3 class="text-xl font-bold text-gray-900 dark:text-white">Los Vengadores</h3>
                         <p class="text-sm text-gray-500 dark:text-gray-400">Líderes de la Liga A</p>
                     </div>
                     <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 flex flex-col items-center">
                         <img class="w-24 h-24 mb-4 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png" alt="imagen del equipo">
                         <h3 class="text-xl font-bold text-gray-900 dark:text-white">Halcones Rojos</h3>
                         <p class="text-sm text-gray-500 dark:text-gray-400">Invictos en el último mes</p>
                     </div>
                     <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 flex flex-col items-center">
                         <img class="w-24 h-24 mb-4 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png" alt="imagen del equipo">
                         <h3 class="text-xl font-bold text-gray-900 dark:text-white">Deportivo Central</h3>
                         <p class="text-sm text-gray-500 dark:text-gray-400">Semifinalistas del torneo</p>
                     </div>
                     <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 flex flex-col items-center">
                         <img class="w-24 h-24 mb-4 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png" alt="imagen del equipo">
                         <h3 class="text-xl font-bold text-gray-900 dark:text-white">Los Guerreros</h3>
                         <p class="text-sm text-gray-500 dark:text-gray-400">Campeones de la última temporada</p>
                     </div>
                 </div>
             </div>
         </section>

         <section id="unete" class="py-20 bg-gray-900 dark:bg-gray-800 text-white text-center">
             <h2 class="text-3xl font-bold">¡Regístrate y comienza a jugar!</h2>
             <p class="mt-4 text-lg">No esperes más para vivir la pasión del fútbol en el mejor lugar. Únete a nuestra comunidad.</p>
             <a href="#" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900 mt-8">
                 Registrarse
             </a>
         </section>
     </x-layouts.app.frontend>
 </div>