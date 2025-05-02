<template>
    <div class="p-6 bg-white rounded-lg shadow-md">

        <h3 class="text-2xl font-bold mb-4 text-gray-800">Lista de tareas:</h3>

        <div class="mb-2">
            <button @click="mostrarModal = true"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Abrir Modal
            </button>
        </div>
        <div class="mb-2">
            <button @click="toLoginRedirect()"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-blue-700 transition">
                Iniciar sesion
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">#
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Nombre</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Descripción</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(item, index) in tareas" :key="index" class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2 text-sm text-gray-700">{{ index + 1 }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ item.name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ item.description }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">
                            <!-- Aquí puedes poner botones de acción después -->
                            <button
                                class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition" @click="find(item.id)">Editar</button>
                            <button
                                class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition ml-2" @click="deleteTask(item.id)">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div v-if="mostrarModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">

            <h3 class="text-2xl font-semibold text-center mb-4">{{ (tarea.id>0) ? 'Actualizar tarea': 'Registrar tarea'}}</h3>
            <p class="mb-4 text-gray-700">
            <form @submit.prevent="guardar(tarea)" class="w-full max-w-lg mx-auto p-6 bg-white shadow-lg rounded-lg">

                <input type="hidden" v-model="tarea.id">
                <input type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nombre de la tarea" v-model="tarea.name">
                <input type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Descripción de la tarea" v-model="tarea.description">
                <div class="flex justify-end space-x-2">
                    <button @click="resetearValores()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        {{ (tarea.id>0) ? 'Actualizar': 'Registrar'}}
                    </button>
                </div>

            </form>
            </p>



            <!-- Botón de cerrar (X) -->
            <button @click="resetearValores()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                ✖
            </button>

        </div>
    </div>
</template>


<script>
import axios from 'axios';

export default {
    name: 'ComponenteTareas',
    data() {
        return {
            mostrarModal: false,
            tareas: [],
            tarea: {id: 0, name: '', description: '' }
        }
    },
    created() {
        axios.get('api/tareas/all').then(res => {
            console.log(res);
            this.tareas = res.data.tareas;
        })
    },
    methods: {

        guardar(tarea) {
            Swal.fire({
                title: 'Cargando...',
                html: 'Por favor espera un momento',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('/tareas/save', tarea)
            .then(res=> {
                const tareaNew=res.data.tarea;

                this.tareas= (res.data.op === 'ACTUALIZAR') ?
                this.tareas.map(t => (t.id == tareaNew.id) ? { ...tareaNew } : t)
                :
                [... this.tareas, {...tareaNew}];

                Swal.fire('Exito!', res.data.message, 'success');
                this.resetearValores();
            })

        },
        find(id){
            axios.get(`/tareas/find/${id}`)
            .then(res=>{
                const tarea=res.data.tarea;
                this.tarea = { ...tarea};

                this.mostrarModal=true;
            })
        },
        deleteTask(id){
            Swal.fire({
                title: "Estas seguro?",
                text: "No podrás revertir esta acción",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!"
                }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Cargando...',
                        html: 'Por favor espera un momento',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    axios.delete(`/tareas/delete/${id}`)
                    .then(res =>{
                        this.tareas=this.tareas.filter(t => t.id !== id);
                        Swal.fire({
                            title: "Eliminado!",
                            text: res.data.message,
                            icon: "success"
                        });
                    })

                }
            });
        },
        resetearValores(){
            this.mostrarModal=false;
            this.tarea={ name: '', description: '' };
        },
        toLoginRedirect(){
            this.$router.push('/login');
        }

    },
}
</script>
