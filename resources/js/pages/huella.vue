<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md text-center">
      <h1 class="text-2xl font-bold mb-6 text-gray-800">Registro Biométrico</h1>
      
      <div class="mb-8">
        <div class="bg-indigo-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09m8.19.821c1.977-3.22 3.054-6.955 3.054-10.925 0-.83-.053-1.647-.155-2.451m-12.259 1.821a9.856 9.856 0 011.037-4.957m1.921-.921a9.733 9.733 0 014.419-1.056c2.385 0 4.59.851 6.305 2.267m-8.45 7.546c.564.04 1.134.06 1.714.06 1.646 0 3.184-.138 4.68-.395m-5.65 2.43l.747-.747" />
          </svg>
        </div>
        <p class="text-gray-600">Pulsa el botón para activar el lector de huellas de tu Infinix</p>
      </div>

      <button 
        @click="autenticarHuella"
        :disabled="cargando"
        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition-all active:scale-95 disabled:opacity-50"
      >
        {{ cargando ? 'Esperando huella...' : 'Escanear Huella' }}
      </button>

      <div v-if="mensaje" :class="`mt-4 p-3 rounded ${error ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}`">
        {{ mensaje }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const cargando = ref(false);
const mensaje = ref('');
const error = ref(false);

const autenticarHuella = async () => {
  if (!window.PublicKeyCredential) {
    error.value = true;
    mensaje.value = "Tu navegador no soporta autenticación biométrica.";
    return;
  }

  cargando.value = true;
  mensaje.value = '';

  try {
    // Configuración mínima para pedir biometría (Fingerprint/FaceID)
    const publicKeyCredentialCreationOptions = {
      challenge: Uint8Array.from("solohuella-seguridad-123", c => c.charCodeAt(0)),
      rp: { name: "Solo Huella App", id: window.location.hostname },
      user: {
        id: Uint8Array.from("USER_ID_789", c => c.charCodeAt(0)),
        name: "usuario@ejemplo.com",
        displayName: "Usuario Infinix"
      },
      pubKeyCredParams: [{ alg: -7, type: "public-key" }], // ES256
      authenticatorSelection: { authenticatorAttachment: "platform" }, // Fuerza a usar el sensor del celular
      timeout: 60000
    };

    const credential = await navigator.credentials.create({
      publicKey: publicKeyCredentialCreationOptions
    });

    console.log("Credencial generada:", credential);
    mensaje.value = "¡Huella reconocida con éxito!";
    error.value = false;
  } catch (err) {
    console.error(err);
    error.value = true;
    mensaje.value = "Error o cancelación del escaneo.";
  } finally {
    cargando.value = false;
  }
};
</script>