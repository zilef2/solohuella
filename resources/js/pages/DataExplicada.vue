<template>
    <div class="flex justify-between">
        <span class="font-semibold text-gray-600 dark:text-white">Radicación:</span>
        <span class="text-gray-900 dark:text-white">{{ props.proceso ? props.proceso.llaveProceso : '' }}</span>
    </div>
    <div class="flex justify-between">
        <span class="font-semibold text-gray-600 dark:text-white">Fecha de inicio:</span>
        <span class="text-gray-900 dark:text-white">{{ props.proceso ? formatearFecha(props?.proceso.fechaProceso) : '' }}</span>
    </div>
    <div class="flex justify-between">
        <span class="font-semibold text-gray-600 dark:text-white">Última actuación:</span>
        <span class="text-gray-900 dark:text-white">{{ props?.proceso ? formatearFecha(props.proceso.fechaUltimaActuacion) : '' }}</span>
    </div>
    <div class="flex justify-between">
        <span class="font-semibold text-gray-600 dark:text-white">Despacho:</span>
        <span class="text-right text-gray-900 dark:text-white">{{ props.proceso ? props?.proceso.despacho : '' }}</span>
    </div>
    <div class="flex justify-between">
        <span class="font-semibold text-gray-600 dark:text-white">Departamento:</span>
        <span class="text-gray-900 dark:text-white">{{ props.proceso ? props?.proceso.departamento : '' }}</span>
    </div>
    <div v-if="props.proceso" class="flex flex-col">
        <span class="mb-1 font-semibold text-gray-600 dark:text-white">Sujetos Procesales:</span>
        <div class="rounded p-2 text-sm leading-relaxed text-gray-800 dark:text-white">

            <ul v-if="sujetosProcesalesDivididos.length">
                <li v-for="(sujeto, index) in sujetosProcesalesDivididos" :key="index" 
                class="bg-gray-50 text-black dark:bg-black dark:text-white">
                    {{ sujeto.trim() }}
                </li>
            </ul>
        </div>
    </div>
</template>
<script setup lang="ts">
import { formatearFecha } from '@/global.ts';
import { computed, defineProps, reactive } from 'vue';

const data = reactive({
    sujetos: [],
});

// 1. Definir los props que tu componente espera recibir
// Usamos una interfaz para una mejor tipificación con TypeScript
interface ProcesoProps {
    proceso: {
        sujetosProcesales?: string; // El signo de pregunta indica que es opcional
    };
}

const props = defineProps<ProcesoProps>();

// 2. Crear la propiedad computada
// Esta función se ejecutará cada vez que 'props.proceso.sujetosProcesales' cambie.
const sujetosProcesalesDivididos = computed<string[]>(() => {
    if (props.proceso && props.proceso.sujetosProcesales) {
        return props.proceso.sujetosProcesales.split('|');
    }
    return [];
});
</script>
