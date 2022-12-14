<template>
    <div>
        WaitForConfirmation
    </div>
</template>
<script>

import useEventBus from '../../../composables/useEventBus'

export default {
    name: "WaitForConfirmation",
    components: {

    },
    methods: {
    
    },
    mounted(){
        const { emitEvent } = useEventBus();
        emitEvent('eventWaitForConfirmation');
    }
}
</script>
<style scoped>

</style>