 <template>
    <multiselect class="search-box" 
        :value="active"
        @input="setActiveComparison"
        :options="allComparisons" 
        placeholder="Active Comparison" 
        selectLabel=">" 
        deselectLabel="x">
    </multiselect>
</template>


<script>
    import { mapGetters, mapActions } from 'vuex';
    
    export default {
        name: 'comparison-active-select',

        data() {
            return {
                
            }
        },

        computed: {
            ...mapGetters('comparison', [
                'active',
                'compare'
            ]),

            allComparisons() {
                let active = this.active;
                let comps = this.compare;
                let array = ['Primary'];
                for (const key in comps) {
                    let num = parseInt(key) + 1;
                    array.push('Comparison ' + num);
                }
                return array;
            }
        },

        methods: {
            ...mapActions('comparison', [
                'setActiveComparison'
            ])
        }
    }
</script>
