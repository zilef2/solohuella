/*
-- this Project

--FROM others projects

 // --DATE functions
 formatearFecha
 formatToVue
 formatDate
 monthName
 TransformTdate

 --MATHfunctionsome

 number_format
 CalcularEdad
 CalcularSexo
 CalcularAvg

 --STRING FUNCTIONS

 sinTildes
 NoUnderLines
 ReemplazarTildes
 PrimerosCaracteres
 PrimerasPalabras
 textoSinEspaciosLargos

 --ARRAY

 vectorSelect
*/


//FROM others projects


//fin FROM others prjects
// this Project
export function LookForValueInArray(arrayOfObjects: object[], searchValue : any): string {
    //ex: { title: '123', value: 1 },
    let foundObject:string = '';
    for (const obj of arrayOfObjects as any) {

        if (typeof searchValue == 'string') {
            // if (obj['title'] === searchValue) {
            //     foundObject = obj['value'];
            // }
            return '';
        } else {
            if (obj['title'] === searchValue['title']) {
                foundObject = obj['value'];
                break;
            }
        }
    }

    return foundObject;
}

// end this Project


// --DATE functions
export function formatearFecha(fecha: string): string {
    if(!fecha) return '';
    return new Date(fecha).toLocaleDateString('es-CO', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
}
export function weekNumber(date: Date): number {
    if (!(date instanceof Date) || isNaN(date.getTime())) {
        return 0;
    }

    // Clonar la fecha para no modificar el objeto original
    const clonedDate = new Date(date.getTime());
    clonedDate.setHours(0, 0, 0, 0); // Configurar al inicio del día

    // Obtener la fecha del 1 de enero del mismo año
    const startOfYear = new Date(clonedDate.getFullYear(), 0, 1);
    startOfYear.setHours(0, 0, 0, 0); // Configurar al inicio del día

    // Calcular el día de la semana del 1 de enero (0 = domingo, 6 = sábado)
    const startDayOfWeek = startOfYear.getDay();

    // Ajustar para que la semana comience en lunes (ISO 8601)
    const dayOfYear = Math.floor((clonedDate.getTime() - startOfYear.getTime()) / (24 * 60 * 60 * 1000)) + 1;
    const adjustedDayOfWeek = (startDayOfWeek === 0 ? 7 : startDayOfWeek); // Convertir domingo (0) en 7
    const weekNumber = Math.ceil((dayOfYear + adjustedDayOfWeek - 1) / 7);

    return weekNumber;
}


export function weekNumberv2(date : any) {//todo: quiero saber porque el 14 enero de 2025 era la semana 2 !absurdo¡
    if (!(date instanceof Date) || isNaN(date.getTime())) {
        return 0
    }
    // Clonar la fecha para no modificar el objeto original
    const clonedDate: Date = new Date(date.getTime());

    // Configurar el tiempo al inicio del día para evitar problemas con las zonas horarias
    clonedDate.setHours(0, 0, 0, 0);

    // Obtener la fecha de inicio del año
    const startOfYear: Date = new Date(clonedDate.getFullYear(), 0, 1);

    // Calcular la diferencia en milisegundos entre la fecha dada y el inicio del año
    let timeDiff: any;
    // @ts-ignore
    timeDiff = clonedDate - startOfYear;

    // Calcular el número de semanas completas y redondear hacia abajo
    let weekNumber = Math.floor(timeDiff / (7 * 24 * 60 * 60 * 1000));

    // Agregar 1 para contar la primera semana del año
    weekNumber += 1;

    return weekNumber;
}

export function formatToVue(date): string {
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
}

export function Date_to_html(date: Date): string {
    if (!(date instanceof Date) || isNaN(date.getTime())) {
        return ''
    }
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    const day2: string = day < 10 ? '0' + day : day + '';

    const month2: string = month < 10 ? '0' + month : month + '';

    return `${year}-${month2}-${day2}`;
}
export function DateTime_to_html(StringDate : any): string {
    if (!StringDate) {
        return '';
    }
    const date : Date = new Date(StringDate)
    const day : number = date.getDate();
    const month : number = date.getMonth() + 1;
    const year : number = date.getFullYear();
    const hours : number = date.getHours();
    const minutes : number = date.getMinutes();
    
    // Formatear con ceros a la izquierda cuando sea necesario
    const day2 = day < 10 ? '0' + day : day.toString();
    const month2 = month < 10 ? '0' + month : month.toString();
    const hours2 = hours < 10 ? '0' + hours : hours.toString();
    const minutes2 = minutes < 10 ? '0' + minutes : minutes.toString();
    
    // Formato YYYY-MM-DDThh:mm para input datetime-local
    return `${year}-${month2}-${day2}T${hours2}:${minutes2}`;
}

export function Now_Date_to_html(): string {
    const hoy:Date = new Date();
    const day:number = hoy.getDate();
    const day2: string = day < 10 ? '0' + day : day + '';
    const month: number = hoy.getMonth() + 1;
    const month2: string = month < 10 ? '0' + month : month + '';
    const year = hoy.getFullYear();

    return `${year}-${month2}-${day2}`;
}


export function TimeTo12Format(timeString) {
    if (timeString === null) return '';
    const [hours, minutes, seconds] = timeString.split(':');

    // Convert the time to 12-hour format and add 'am' or 'pm'
    return new Date(2023, 7, 5, parseInt(hours), parseInt(minutes)).toLocaleString('es-CO', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
    });
}

export function formatTime(date): string {
    let validDate
    if (date) {
        validDate = new Date(date)
    } else {
        validDate = new Date()
    }
    // validDate = new Date(validDate.getTime() + (5 * 60 * 60 * 1000))

    const hora = validDate.getHours();
    const hourAndtime = hora + ':' + (validDate.getMinutes() < 10 ? '0' : '') + validDate.getMinutes() + ':00';

    return `${hourAndtime}`;
}

export function TransformTdate(number: any = null, dateString = new Date()) {
    let date
    if (dateString) {
        date = new Date(dateString);
    } else {
        date = new Date();
    }
    const year = date.getFullYear();
    const month = ('0' + (date.getMonth() + 1)).slice(-2);
    const day = ('0' + date.getDate()).slice(-2);

    let hours
    if (number) {
        hours = number >= 10 ? number : '0' + number;
    } else {
        hours = ('0' + date.getHours() + number).slice(-2);
    }
    const minutes = number ? '00' : ('0' + date.getMinutes()).slice(-2);
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}


export function monthName(monthNumber)   {
    if (monthNumber == 1) return 'Enero';
    if (monthNumber == 2) return 'Febrero';
    if (monthNumber == 3) return 'Marzo';
    if (monthNumber == 4) return 'Abril';
    if (monthNumber == 5) return 'Mayo';
    if (monthNumber == 6) return 'Junio';
    if (monthNumber == 7) return 'Julio';
    if (monthNumber == 8) return 'Agosto';
    if (monthNumber == 9) return 'Septiembre';
    if (monthNumber == 10) return 'Octubre';
    if (monthNumber == 11) return 'Noviembre';
    if (monthNumber == 12) return 'Diciembre';
}

export function allMonthName(): { id: number, name: string }[] {
    return [
        {
            'id': 1,
            'name': 'Enero'
        },
        {
            'id': 1,
            'name': 'Febrero'
        },
        {
            'id': 1,
            'name': 'Marzo'
        },
        {
            'id': 1,
            'name': 'Abril'
        },
        {
            'id': 1,
            'name': 'Mayo'
        },
        {
            'id': 1,
            'name': 'Junio'
        },
        {
            'id': 1,
            'name': 'Julio'
        },
        {
            'id': 1,
            'name': 'Agosto'
        },
        {
            'id': 1,
            'name': 'Septiembre'
        },
        {
            'id': 1,
            'name': 'Octubre'
        },
        {
            'id': 1,
            'name': 'Noviembre'
        },
        {
            'id': 1,
            'name': 'Diciembre'
        },
    ]
}


// fin DATE functions


// MATHfunctionsome

export function formatPesosCol(number): string {
    number = Math.round(number);
    const formattedNumber = number.toLocaleString('en-US', {
        minimumFractionDigits: 0, // Mínimo de dígitos decimales (en este caso, 0)
        maximumFractionDigits: 0 // Máximo de dígitos decimales (en este caso, 0)
    });

    // Reemplazar la coma por un punto como separador decimal
    console.log("🚀 ~ formatPesosCol ~ formattedNumber: ", formattedNumber);
    return '$ ' + formattedNumber;
    // return '$ ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "'");
}

export function CalcularAvg(TheArray, NameValue = '', isTime = false) {
    let sum = 0
    if (NameValue === '') {
        TheArray.forEach((value, index, array) => {
            sum += value;
        })
    } else {
        if (isTime) { //time like: 14:18

            TheArray.forEach((value, index, array) => {
                let justHour = value[NameValue].split(':')[0];
                justHour = parseInt(justHour);
                sum += justHour;
            })
        } else {
            TheArray.forEach((value, index, array) => {
                sum += value[NameValue];
            })
        }
    }
    return number_format(sum / TheArray.length, 1, false);
}

export function number_format(amount: any, decimals: number = 0, isPesos = false) {
    if (typeof amount !== 'string' && typeof amount !== 'number') return '0';

    // Convertir a string y asegurarse de que el signo negativo no se elimine
    amount = amount.toString().replace(/[^0-9\.-]/g, '');

    const num: number = parseFloat(amount);
    if (isNaN(num)) return (0).toFixed(decimals);

    // Formatear el número con la cantidad de decimales deseada
    const num2 = num.toFixed(decimals);

    let [integerPart, decimalPart] = num2.split('.');

    // Agregar separadores de miles con un regex
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    // Construir el resultado final
    const formattedNumber = decimalPart ? `${integerPart},${decimalPart}` : integerPart;
    return isPesos ? `$${formattedNumber}` : formattedNumber;
}


export function CalcularEdad(nacimiento) {
    const anioHoy = new Date().getFullYear();
    const anioNacimiento = new Date(nacimiento).getFullYear();
    return anioHoy - anioNacimiento;
}

export function CalcularSexo(value) {
    return value == 0 ? 'Masculino' : 'Femenino'
}


//STRING FUNCTIONS
export function sinTildes(value) {
    const pattern = /[^a-zA-Z0-9\s]/g;
    const replacement = '';
    const result = value.replace(pattern, replacement);
    return result
}

export function NoUnderLines(value) {
    return value.replace(/[^a-zA-Z0-9]/g, ' ');
}


export function ReemplazarTildes(texto) {
    return texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

export function PrimerosCaracteres(texto, caracteres = 15) {
    if (texto) {

        if (texto.length > caracteres + 5) {

            const primeros = texto.substring(0, caracteres);
            return primeros + '...';
        }
        return texto
    }
}

export function PrimerasPalabras(texto, palabras = 10) {
    if (texto) {
        const firstWords = texto.split(" ");
        if (firstWords.length > palabras) {
            const primeros = firstWords.slice(0, palabras).join(" ");
            return primeros + '...';
        }
        return texto
    }
}

export function textoSinEspaciosLargos(texto) {
    return texto.replace(/\s+/g, ' ');
}


//array functions
export function vectorSelect(vectorSelect, propsVector, genero = 'uno') {
    vectorSelect = propsVector.map(
        generico => (
            {label: generico.nombre, value: generico.id}
        )
    )
    vectorSelect.unshift({label: 'Seleccione ' + genero, value: 0})
    return vectorSelect;
}

/*
watch(() => form.tipoRes, (newX) => {
    data.selectedPrompID = 'Selecciona un promp'
})
*/