export interface Actuacion {
    idRegActuacion: number
    llaveProceso: string
    consActuacion: number
    fechaActuacion: string
    actuacion: string
    anotacion: string
    fechaInicial: string
    fechaFinal: string
    fechaRegistro: string
    codRegla: string
    conDocumentos: boolean
    cant: number
}


// Define la interfaz para la estructura del objeto 'proceso'
export interface ProcesoDetalle {
    validacioncini?: boolean; // Ajusta el tipo si no es boolean (ej. string '1'/'0')
    esPrivado?: string; // Asumo que es '1' o '0' como string
    sujetosProcesales?: string;
    llaveProceso?: string;
    fechaProceso?: string;
    fechaUltimaActuacion?: string;
    despacho?: string;
    departamento?: string;
}

export interface ProcesoDataType {
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