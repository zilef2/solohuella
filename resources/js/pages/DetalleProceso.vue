<template>
    <div
        class="mx-auto my-10 max-w-3xl rounded-xl bg-white p-8 text-gray-800 shadow-lg transition-all duration-300 dark:bg-gray-900 dark:text-gray-100 dark:shadow-2xl"
    >
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 2xl:gap-12 2xl:grid-cols-3">
            <div
                class="flex flex-col md:col-span-2 2xl:col-span-3 rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:shadow-xl dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Última Actualización:</span>
                
                <p class="text-lg font-medium">{{ formatDateTime(proceso.ultimaActualizacion) }} - <span class="inline-flex text-lg text-indigo-600">{{props.UltimaActuacion}}</span></p>
            </div>
            <div
                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:scale-105 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Despacho:</span>
                <span class="text-lg font-medium">{{ proceso.despacho }}</span>
            </div>
            <div
                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:scale-105 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Ponente:</span>
                <span class="text-lg font-medium">{{ proceso.ponente }}</span>
            </div>
            <div
                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:scale-105 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Fecha Proceso:</span>
                <span class="text-lg font-medium">{{ formatDate(proceso.fechaProceso) }}</span>
            </div>

            <div
                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:scale-105 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Tipo Proceso:</span>
                <span class="text-lg font-medium">{{ proceso.tipoProceso }}</span>
            </div>
            <div
                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:scale-105 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Clase Proceso:</span>
                <span class="text-lg font-medium">{{ proceso.claseProceso }}</span>
            </div>
            <div
                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:scale-105 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Subclase Proceso:</span>
                <span class="text-lg font-medium">{{ proceso.subclaseProceso }}</span>
            </div>
            <div
                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:scale-105 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Recurso:</span>
                <span class="text-lg font-medium">{{ proceso.recurso }}</span>
            </div>
<!--            <div-->
<!--                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"-->
<!--            >-->
<!--                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Es Privado:</span>-->
<!--                <span class="text-lg font-medium">{{ proceso.esPrivado ? 'Sí ✅' : 'No ❌' }}</span>-->
<!--            </div>-->
            <div
                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Ubicación:</span>
                <span class="text-lg font-medium">{{ proceso.ubicacion || 'No especificada' }}</span>
            </div>
            <div
                class="flex flex-col rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 transition-all duration-100 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
                <span class="mb-1 text-sm font-semibold text-gray-600 dark:text-gray-400">Contenido Radicación:</span>
                <span class="text-lg font-medium">{{ proceso.contenidoRadicacion || 'No especificado' }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

// Interfaz para definir la estructura esperada del objeto 'proceso'
interface ProcesoData {
    idRegProceso: number;
    llaveProceso: string;
    idConexion: number;
    esPrivado: boolean;
    fechaProceso: string;
    codDespachoCompleto: string;
    despacho: string;
    ponente: string;
    tipoProceso: string;
    claseProceso: string;
    subclaseProceso: string;
    recurso: string;
    ubicacion: string | null;
    contenidoRadicacion: string | null;
    fechaConsulta: string;
    ultimaActualizacion: string;
}

const props = defineProps({
    proceso: {
        type: Object as () => ProcesoData, // Usamos una función para tipar el objeto
        required: true,
    },
    UltimaActuacion: String,
    
});

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-CO', { year: 'numeric', month: 'long', day: 'numeric' });
};

const formatDateTime = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-CO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true, // Para formato AM/PM
    });
};
</script>

<style scoped>
/* Define la animación de brillo para Tailwind */
@keyframes shine {
    0% {
        transform: translateX(-100%) skewX(-20deg);
    }
    50% {
        transform: translateX(200%) skewX(-20deg);
    }
    100% {
        transform: translateX(-100%) skewX(-20deg);
    }
}

/* Agrega la animación a Tailwind */
.animate-shine {
    animation: shine 3s infinite ease-in-out;
    animation-delay: 1s;
}
</style>
