<script setup>
import { Head, router, } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import axios from 'axios';
import { onMounted, ref, watch } from 'vue'

const busqueda = ref('');
const orden = ref('asc');
const inputNombre = ref('');
const inputAño = ref('');
const inputDuracion = ref('');
const inputGenero = ref('');
const inputDirector = ref('');
const inputActores = ref('');
const inputSinopsis = ref('');

const busquedas = ref([]);
const links = ref([]);
const from = ref(0);
const to = ref(0);
const total = ref(0);

const searchMovies = async () => {
    const response = await axios.get("http://www.omdbapi.com/?apikey=99e2c578&t=" + busqueda.value);
    console.log(response.data);
    if (response.data.Response === "False"){
        alert("No se encontraron resultados");
    }else{
        await axios.post('/peliculas', {
        nombre: response.data.Title,
        año: response.data.Year,
        duracion: response.data.Runtime,
        genero: response.data.Genre,
        director: response.data.Director,
        actores: response.data.Actors,
        sinopsis: response.data.Plot,
        imagen: response.data.Poster
        });
        router.reload();
    }
   
    busqueda.value = '';
}

const ordenar = async (columna) => {
    orden.value = orden.value === 'asc' ? 'desc' : 'asc';
    const response = await axios.get('/peliculas/orden', {
        params:{
            columna : columna,
            orden : orden.value
        }
    });
    console.log(response.data);
    busquedas.value = response.data.busquedas;
}


watch(inputNombre, async (newValor) => {
    busquedaPorColumna('nombre', newValor);
});

watch(inputAño, async (newValor) => {
    busquedaPorColumna('año', newValor);
});

watch(inputDuracion, async (newValor) => {
    busquedaPorColumna('duracion', newValor);
});

watch(inputGenero, async (newValor) => {
    busquedaPorColumna('genero', newValor);
});

watch(inputDirector, async (newValor) => {
    busquedaPorColumna('director', newValor);
});

watch(inputActores, async (newValor) => {
    busquedaPorColumna('actores', newValor);
});

watch(inputSinopsis, async (newValor) => {
    busquedaPorColumna('sinopsis', newValor);
});

const busquedaPorColumna = async (columna, valor) => {
    const respuesta = await axios.get('/peliculas/busqueda', {
        params:{
            columna : columna,
            valor : valor
        }
    });    
    busquedas.value = respuesta.data.busquedas;
}

    // Función para obtener los datos de la API
    const fetchBusquedas = (url) => {
      axios.get(url).then((response) => {
        busquedas.value= response.data.busquedas.data;
        from.value = response.data.busquedas.from;
        to.value = response.data.busquedas.to;
        total.value = response.data.busquedas.total;
        links.value = response.data.busquedas.links;
      });
    };

const changePage = (url) => {
      fetchBusquedas(url); // Cambia la página cargando los datos de esa URL
    };

onMounted(() => {
    fetchBusquedas('/peliculas/all'); // Ajusta la URL de la API si es necesario
});
</script>

<template>
    <Head title="Buscador de peliculas" />
    <div class="container">
        <h1>BUSCA TU PELICULA FAVORITA</h1><br><br>

        <form class="row g-3" >
            <div class="col-auto">
                <input v-model="busqueda" type="text" class="form-control" id="inputPassword2" placeholder="Nombre de pelicula" >
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary mb-3" @click="searchMovies">Search</button>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th @click="ordenar('nombre')">Nombre</th>
                    <th @click="ordenar('año')">Año</th>
                    <th @click="ordenar('duracion')">Duracion</th>
                    <th @click="ordenar('genero')">Genero</th>
                    <th @click="ordenar('director')">Director</th>
                    <th @click="ordenar('actores')">Actores</th>
                    <th @click="ordenar('sinopsis')">Sinopsis</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="input-group">
                            <input type="text" aria-label="Last name" class="form-control" v-model="inputNombre">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" aria-label="Last name" class="form-control" v-model="inputAño">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" aria-label="Last name" class="form-control" v-model="inputDuracion">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" aria-label="Last name" class="form-control" v-model="inputGenero">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" aria-label="Last name" class="form-control" v-model="inputDirector">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" aria-label="Last name" class="form-control" v-model="inputActores">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" aria-label="Last name" class="form-control" v-model="inputSinopsis">
                        </div>
                    </td>
                </tr>
                <tr v-for="b in busquedas" :key="b.id">
                    <td>{{ b.nombre }}</td>
                    <td>{{ b.año }}</td>
                    <td>{{ b.duracion }}</td>
                    <td>{{ b.genero }}</td>
                    <td>{{ b.director }}</td>
                    <td>{{ b.actores }}</td>
                    <td>{{ b.sinopsis }}</td>
                </tr>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li>Mostrando del {{ from }} al {{ to }} de un total de {{ total }}</li><br>
                <li v-for="link in links" :key="link.label" class="page-item" :class="{ active: link.active }">
                    <a class="page-link" href="#" @click.prevent="changePage(link.url)">{{ link.label }}</a>
                </li>
            </ul>
        </nav>

        
    </div>
  

</template>

<style>
.container {
  background-color: white; /* O el color que desees */
  color: black; /* Asegúrate de que el texto también sea legible */
  height: 950px;
}

button{
    margin-left: 1%;
}

</style>