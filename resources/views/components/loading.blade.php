<div id="loading-component" class="text-red-500 font-bold text-center">
    loading....
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var loadingComponent = document.getElementById('loading-component');
        if (loadingComponent) {
            console.log('Loading component is activated');
        } else {
            console.log('Loading component is not activated');
        }
    });
</script>

