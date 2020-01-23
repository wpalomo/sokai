<template>
  <form v-on:submit.prevent="loginuser()">
    <div>
      <vs-input
        v-validate="'required|email|min:5'"
        data-vv-validate-on="blur"
        name="email"
        icon-no-border
        icon="icon icon-user"
        icon-pack="feather"
        v-bind:label-placeholder="loginemail"
        v-model="email"
        class="w-full"
        style="display: inline-block"
      />
      <span
        class="text-danger text-sm mt-3"
        v-show="errors.first('email')"
        style="display: block;"
      >Ingrese un Correo válido</span>
      <vs-input
        data-vv-validate-on="blur"
        v-validate="'required|min:6'"
        type="password"
        name="password"
        icon-no-border
        icon="icon icon-lock"
        icon-pack="feather"
        v-bind:label-placeholder="loginpass"
        v-model="password"
        class="w-full mt-5 pt-5"
        style="display: inline-block"
      />
      <span
        class="text-danger text-sm mt-3 mb-5"
        v-show="errors.first('password')"
        style="display: block;"
      >La contraseña debe tener almenos 6 dígitos</span>
      <vs-alert
        v-show="error"
        color="danger"
        title="CREDENCIALES INVÁLIDAS"
        active="true"
        class="text-center mt-5"
      >CREDENCIALES INCORRECTAS</vs-alert>
      <div class="flex flex-wrap justify-between mb-3 mt-5">
        <vs-button class="w-full mt-5" :disabled="!validateForm" @click="loginuser()">{{ $t('logininiciar') }}</vs-button>       
      </div>
    </div>
    <button type="submit" style="display:none"></button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: "",
      checkbox_remember_me: false,
      loginemail: this.$t("loginemail"),
      loginpass: this.$t("loginpass"),
      error: false
    };
  },
  computed: {
    validateForm() {
      return this.email != "" && this.password != "";
    }
  },
  methods: {
    loginuser() {
      if (this.errors.any()) {
        return;
      }
      this.error = false;
      this.$vs.loading();
      const payload = {
        checkbox_remember_me: this.checkbox_remember_me,
        userDetails: { email: this.email, password: this.password }
      };
      this.$store
        .dispatch("loginAction", payload)
        .then(res => {
          this.$vs.loading.close();
          //this.$router.push("/");
          setTimeout(function(){ 
            location.reload();
          }, 1000);
        })
        .catch(error => {
          this.$vs.loading.close();
          this.error = true;
        });
    },
    registerUser() {
      this.$router.push("/registro").catch(() => {});
    }
  }
};
</script>

