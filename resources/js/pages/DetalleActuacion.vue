<template>
    <div class="mx-auto my-10 max-w-3xl rounded-xl bg-white p-8 text-gray-800 shadow-lg transition-all duration-300 dark:bg-gray-900 dark:text-gray-100 dark:shadow-2xl">
        <h3 class="mx-auto text-center mb-4 text-xl font-bold text-blue-600 dark:text-blue-400">Historial de Actuaciones</h3>

        <div v-if="props.actuaciones.length === 0" class="text-gray-500 dark:text-gray-400">
            No hay actuaciones disponibles.
        </div>

        <div v-else class="space-y-4">
            <div
                v-for="(actuacion, index) in props.actuaciones"
                :key="actuacion.idRegActuacion"
                class="rounded-lg border-l-4 border-blue-500 bg-gray-50 p-4 shadow-sm transition-all duration-100 hover:shadow-md dark:border-blue-600 dark:bg-gray-800"
            >
<!--                asdasd {{index}}-->
<!--                <pre v-if="index=== 0 || index === 1" class="text-xs"> {{actuacion}}</pre>  -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        {{ actuacion.actuacion }}
                    </h3>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ formatDate(actuacion.fechaActuacion) }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ actuacion.anotacion || 'Sin anotaciones.' }}
                </p>
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    <span class="mr-4">Cons Actuación: {{ actuacion.consActuacion }}</span>
                    <span v-if="actuacion.fechaInicial">Desde: {{ formatDate(actuacion.fechaInicial) }}</span>
                    <span v-if="actuacion.fechaInicial" class="mx-2">→</span>
                    <span v-if="actuacion.fechaInicial">Hasta: {{ formatDate(actuacion.fechaFinal) }}</span>
                    <span v-else>Sin fechas registradas</span>
                </div>
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    <span class="mr-4">Tiene documentos?  {{ actuacion.conDocumentos ? '✅' : '❌' }}</span>
<!--                    <span>Desde: {{ formatDate(actuacion.fechaInicial) }}</span>-->
<!--                    <span class="mx-2">→</span>-->
<!--                    <span>Hasta: {{ formatDate(actuacion.fechaFinal) }}</span>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Actuacion } from './commoninterfaces.ts'



const props = defineProps<{
    actuaciones: Actuacion[],
}>()

const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('es-CO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}
</script>
