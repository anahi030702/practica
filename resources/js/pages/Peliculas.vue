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

const getData = async() => {
    const response = await axios.get('peliculas/all');
    busquedas.value = response.data.busquedas.data;
    console.log(response.data.busquedas.links);
    links.value = response.data.busquedas.links;
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

onMounted(() => {
    getData();
});
</script>

<template>
    <Head title="Buscador de peliculas" />
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    </head>
    <body class="container">
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

        <!-- Paginación -->
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <!-- Previous Button -->
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>

        <!-- Páginas -->
        <li
          v-for="page in totalPages"
          :key="page"
          class="page-item"
          :class="{ active: page === currentPage }"
        >
          <a class="page-link" href="#" @click.prevent="changePage(page)">
            {{ page }}
          </a>
        </li>

        <!-- Next Button -->
        <li class="page-item" :class="{ disabled: currentPage === lastPage }">
          <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>

        
    </body>
    </html>
  

</template>

<style>
body{
    text-align: center;
    margin-top: 3%;
    background-color: white;
    text-align: center;
}

button{
    margin-left: 1%;
}

</style>