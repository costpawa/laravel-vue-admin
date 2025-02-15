<template>
  <v-form @submit.prevent="loginAttempt">
    <v-card class="elevation-12">
      <v-toolbar dark color="primary">
        <v-toolbar-title>Login</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-text-field
          prepend-icon="mdi-account"
          name="login"
          label="E-mail"
          type="text"
          required
          v-model="user.email"
          :error-messages="emailErrors"
          @input="input('email')"
          @blur="input('email')"
        ></v-text-field>
        <v-text-field
          prepend-icon="mdi-lock"
          name="password"
          label="Password"
          type="password"
          required
          v-model="user.password"
          :error-messages="passwordErrors"
          @input="input('password')"
          @blur="input('password')"
        ></v-text-field>
        <v-checkbox
          v-model="user.rememberMe"
          label="Remember me"
          color="primary"
          hide-details
          class="mr-4"
        ></v-checkbox>
      </v-card-text>
      <v-card-actions class="d-sm-flex justify-space-between">
        <!-- <router-link to="/forget-password" class="float-left mt-3 ml-3 text-body-2">Forget password?</router-link> -->
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          class="mr-2 mt-3 mb-2"
          :disabled="disabled"
          :loading="loading"
          type="submit"
        >
          Login
          <template v-slot:loader>
            <span class="custom-loader">
              <v-icon light>mdi-cached</v-icon>
            </span>
          </template>
        </v-btn>
      </v-card-actions>
      <v-alert
        :value="alert"
        type="error"
        transition="scale-transition"
        style="margin-top: 20px; position: absolute; width:100%;"
      >
      {{ errors.message }}
        <div
          v-for="(error, id) in errors.errors"
          :key="id">
          {{ error[0] }}
        </div>
      </v-alert>
    </v-card>
  </v-form>
</template>

<script>
  import { validationMixin } from 'vuelidate';
  import { required, maxLength, minLength, email, } from 'vuelidate/lib/validators';

  export default {
    name: 'Login',
    mixins: [validationMixin],
    validations: {
      user: {
        email: { required, minLength: minLength(6), maxLength: maxLength(50), email },
        password: { required, minLength: minLength(6), maxLength: maxLength(20) },
      }
    },

    data: () => ({
      user: {
        email: "",
        password: "",
        rememberMe: false,
      },
      errors: [],
      disabled: true,
      loading: false,
      alert: false,
    }),

    methods: {
      loginAttempt() {
        this.disabled = true;
        this.loading = true;
        this.alert = false;
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.$store.dispatch('auth/login', this.user)
          .then(uri => {
            this.loading = false;
            this.$router.push(uri);
          })
          .catch(err => {
            // console.log(err);
            this.errors = {
              status: err.response.status,
              message: err.response.data.message,
              errors: err.response.data.errors,
            };
            this.disabled = false;
            this.alert = true;
            this.loading = false;
          })
        }
      },

      input(input) {
        switch (input) {
          case 'email': this.$v.user.email.$touch(); break;
          case 'password': this.$v.user.password.$touch(); break;
        }
        this.disabled = this.$v.$invalid;
      }
    },

    computed: {
      emailErrors () {
        const errors = [];
        if (!this.$v.user.email.$dirty) return errors;
        !this.$v.user.email.minLength && errors.push('Email must be minimum 6 characters long');
        !this.$v.user.email.maxLength && errors.push('Email must be at most 20 characters long');
        !this.$v.user.email.required && errors.push('Email is required.');
        !this.$v.user.email.email && errors.push('Email is not valid.');
        return errors;
      },
      passwordErrors () {
        const errors = [];
        if (!this.$v.user.password.$dirty) return errors;
        !this.$v.user.password.minLength && errors.push('Password must be minimum 6 characters long');
        !this.$v.user.password.maxLength && errors.push('Password must be at most 20 characters long');
        !this.$v.user.password.required && errors.push('Password is required.');
        return errors;
      },
    },

    created() {
      let userStorageName = process.env.VUE_APP_STORAGE_NAME + '_user_';
      let userEmail = localStorage.getItem(userStorageName + 'email');

      if(this.$helpers.isNotNull(userEmail)) {
        this.$router.push("/");
      } else {
        userEmail = sessionStorage.getItem(userStorageName + 'email');
        if(this.$helpers.isNotNull(userEmail)) {
          this.$router.push("/");
        }
      }
    },
  };
</script>