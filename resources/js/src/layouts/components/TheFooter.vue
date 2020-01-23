<!-- =========================================================================================
    File Name: TheFooter.vue
    Description: Footer component
    Component Name: TheFooter
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template functional>
  <footer class="the-footer flex-wrap justify-between" :class="classes">
    <span>
      COPYRIGHT @ {{ new Date().getFullYear() }}
      <a href="https://stbtechnology.ec" target="_blank" rel="nofollow">StbTechnology</a>,
      <a href="https://solmocontadores.com/" target="_blank" rel="nofollow">Solmocontadores</a>, Todos los derechos reservados
    </span>
    <span class="md:flex hidden items-center">
      <span>Stb Technology</span>
      <feather-icon icon="HeartIcon" svgClasses="stroke-current text-danger w-6 h-6" class="ml-2" />
    </span>
  </footer>
</template>

<script>
import BuyNow from "../../components/BuyNow.vue";

export default {
  name: "the-footer",
  props: {
    classes: {
      type: String
    }
  },
  inject: {
    components: {
      default: {
        BuyNow
      }
    }
  }
};
</script>
