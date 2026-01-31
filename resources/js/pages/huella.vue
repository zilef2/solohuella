<template>
    <div class="rounded-lg border bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-lg font-bold text-black">Registro de Alumnos (Biometría)</h3>

        <div class="mb-8 flex flex-col gap-2 text-black">
            <input v-model="nombreAlumno" type="text" placeholder="Nombre completo del alumno" class="rounded border p-2" />
            <button @click="registrarHuella" class="mx-6 rounded bg-indigo-200 px-4 py-2 hover:scale-y-105 hover:bg-indigo-500">
                Registrar Huella
            </button>
        </div>

        <hr class="my-6" />

<!--        <div class="text-center">-->
<!--            <button @click="reconocerAlumno" class="w-full rounded-full bg-emerald-600 px-8 py-4 font-bold text-black shadow-lg hover:bg-emerald-700">-->
<!--                🔍 ESCANEAR HUELLA DE ALUMNO-->
<!--            </button>-->
<!--        </div>-->

        <div v-if="alumno_identificado" class="mt-4 border-l-4 border-blue-500 bg-blue-50 p-4 text-blue-700">
            <p>
                Resultado: <strong>{{ alumno_identificado }}</strong>
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { startAuthentication } from '@simplewebauthn/browser';
import { ref } from 'vue';
// import type { PublicKeyCredentialCreationOptionsJSON } from '@simplewebauthn/types';

const props = defineProps({
    options: Object,
    alumno_identificado: String,
    numberPermissions: Number,
});

const getRandomInt = (min: number, max: number): number => {
  return Math.floor(Math.random() * (max - min + 1)) + min;
};

const miNumero = getRandomInt(1, 10);
const nombregenerado = 'prueba'+miNumero
const nombreAlumno = ref(nombregenerado);

// 1. REGISTRAR ALUMNO
async function registrarHuella() {
    console.log('asdkoansdfhjireqbf.')
    // router.post(
    //     'alumnos.options',
    //     { name: nombreAlumno.value },
    //     {
    //         onSuccess: async () => {
    //             console.log("🚀🚀onSuccess ~ props.options: ", props.options);
    //             // if (!props.options) {
    //             //     console.error('No hay opciones de registro WebAuthn');
    //             //     return;
    //             // }
    //
    //             // const res = await startRegistration({
    //             //     optionsJSON: props.options as PublicKeyCredentialCreationOptionsJSON,
    //             // });
    //             //
    //             // router.post('alumnos.confirmar-registro', {
    //             //     name: nombreAlumno.value,
    //             //     registrationResponse: JSON.stringify(res),
    //             // });
    //         },
    //     },
    // );
}

// 2. RECONOCER ALUMNO
async function reconocerAlumno() {
    // Pedimos desafío de autenticación
    router.get(
        'alumnos.identificar',
        {},
        {
            onSuccess: async () => {
                const res = await startAuthentication(props.options);
                router.post('alumnos.identificar', res);
            },
        },
    );
}
</script>
