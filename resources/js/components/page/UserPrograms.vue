<template>
    <div>
        <!-- User's Current Programs -->
        <div>
            <h4 class="uk-heading-divider">
                Your Programs <span v-if="userPrograms.length > 0">- {{ userPrograms.length }}</span>
                <vk-button size="small" type="danger" class="uk-float-right" v-vk-tooltip.bottom="'Clear All'" @click="removeAll()">
                    <font-awesome-icon class="clickable" icon="times"/>
                </vk-button>
            </h4>

            <span v-for="(program, index) in userPrograms" :key="index">
                <vk-label @click="removeProgram(program)" class="clickable uk-margin-small-right">
                    {{ program }} <font-awesome-icon  class="uk-margin-small-left clickable" icon="times"/>
                </vk-label>
            </span>

        </div>

        <!-- Save Button -->
        <div class="uk-margin-top">
            <vk-button size="small" type="primary" v-vk-tooltip.right="'Save Your Programs'" @click="saveUserPrograms()" class="save-button">
                <font-awesome-icon class="clickable" icon="edit"/>
            </vk-button>
        </div>

        <!-- All Programs with Filter -->
        <div>
            <h4 class="uk-heading-divider uk-margin-top">
                All Programs
                <vk-button size="small" class="uk-float-right" v-vk-tooltip.bottom="'Filter Programs'" @click="toggleFilters()">
                    <font-awesome-icon class="clickable" icon="filter"/>
                </vk-button>
            </h4>
            <div v-if="filters">
                <div class="col-md-3">
                    <location-select @location="setLocation"></location-select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="uk-input search-box" placeholder="Search..." v-model="search">
                </div>
            </div>
            <span v-for="(program, index) in matchingPrograms" :key="index">
                <vk-label @click="addProgram(program.program)" class="clickable uk-margin-small-right">
                    {{ program.program }}
                </vk-label>
            </span>
        </div>

        <!-- Add All Programs -->
        <div class="uk-margin-top">
            <vk-button size="small" v-vk-tooltip.right="'Add All Programs'" @click="addAll()">
                <font-awesome-icon class="clickable" icon="level-up-alt"/>
            </vk-button>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import axios from 'axios';

import LocationSelect from '../selects/BasicLocation';

export default {
    name: 'nexus-user-programs',

    data() {
        return {
            filters: false,
            search: '',
            location: '',
            userPrograms: []
        }
    },

    computed: {
        ...mapGetters('user', [
            'myEmail',
            'myPrograms',
        ]),
        ...mapGetters('attributes', [
            'programs'
        ]),
        matchingPrograms() {
            let list = this.programs;
            let location = this.location;
            let query = this.search;
            let mine = this.userPrograms;

            list = _.filter(list, p => {
                return p.active == 'TRUE';
            });

            if (location != '') {
                list = _.filter(list, p => {
                    return p.location == location;
                });
            }
             
            let match = _.filter(list, p => {
                if(p.program && query) {
                    return p.program.toLowerCase().includes(query.toLowerCase());
                } else {
                    return p;
                }
            });

            let matchOthers = _.filter(match, m => {
                return !mine.includes(m.program);
            })
            return matchOthers;
        }
    },

    methods: {
        ...mapActions('page', [
            'addMessage'
        ]),
        setLocation(location) {
            this.location = location;
        },
        addProgram(program) {
            this.userPrograms.push(program);
        },
        addAll() {
            _.forEach(this.matchingPrograms, p => {
                this.addProgram(p.program);
            })
        },
        removeProgram(program) {
            let start = this.userPrograms;
            let array = start.filter(item => item !== program);
            this.userPrograms = array;
        },
        removeAll() {
            this.userPrograms = [];
        },
        toggleFilters() {
            this.filters = ! this.filters;
        },
        saveUserPrograms() {
            axios
                .post('/update-user-programs', {
                    email: this.myEmail,
                    programs: this.userPrograms
                })
                .then(response => {
                    let data = response.data;
                    if (data.email == this.myEmail) {
                        this.addMessage('Programs Saved!');
                    }
                    console.log(data);
                });
        },
    },

    watch: {
        myPrograms() {
            this.userPrograms = this.myPrograms;
        }
    },

    mounted() {
        this.userPrograms = this.myPrograms;        
    },

    components: {
        LocationSelect
    }
}
</script>

<style scoped>
.clickable {
    cursor: pointer;
}
.save-button {
    background-color: #1f9d55;
    color: #fff;
}
.save-button:hover {
    background-color: #1b924e;
    color: #fff;
}
</style>