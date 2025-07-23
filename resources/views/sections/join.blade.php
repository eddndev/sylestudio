<section id="join" class="relative">
    {{-- Imagen de fondo --}}
    <div class="absolute inset-0">
        <x-responsive-image
            key="join.jpg"
            alt="Community gathering by the sea"
            class="h-full w-full object-cover"
            loading="lazy"
        />
    </div>

    {{-- Overlay oscuro para legibilidad --}}
    <div class="absolute inset-0 bg-black/60 mix-blend-multiply"></div>

    {{-- Contenido sobre la imagen --}}
    {{-- ✅ CORRECCIÓN: Se ajusta la grilla a 3 columnas en 'md', con el contenido ocupando 2 --}}
    <div class="relative grid grid-cols-1 md:grid-cols-3">
        {{-- Bloque de texto a la izquierda --}}
        <div class="md:col-span-2 flex flex-col justify-center px-8 py-16 md:px-20 text-white space-y-6">
            {{-- ✅ CORRECCIÓN: Se ajusta el título para que siempre esté en dos líneas con line-height reducido --}}
            <h2 class="font-teko text-4xl md:text-6xl font-bold uppercase leading-none">
                <span class="block">SE PARTE DE</span>
                <span class="block">NOSOTROS</span>
            </h2>
    
            <p class="max-w-md text-sm">
                It's not for everyone. It's for us.
            </p>
    
            <form action="{{ route('newsletter.store') }}" method="POST" class="max-w-md space-y-6">
                @csrf
                <div>
                    {{-- Asumiendo que tienes un componente x-form-input. Si no, reemplázalo con un <input> normal. --}}
                    <x-form-input name="email" type="email" placeholder="Email address" required onblack />
                </div>
    
                {{-- Asumiendo que tienes un componente x-primary-button. --}}
                <x-primary-button class="w-full md:w-48">
                    Subscribe
                    <svg class="ml-auto h-[24px] w-[24px] stroke-current">
                        <use xlink:href="#icon-arrow-right"/>
                    </svg>
                </x-primary-button>
            </form>
        </div>
        {{-- Columna derecha vacía para mantener el layout de contenido a la izquierda --}}
        <div></div>
    </div>
</section>