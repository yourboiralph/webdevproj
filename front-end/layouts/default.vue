<template>
    <div class="bg-[#b9b9b8]">
        <header class="shadow-2xl fade-in">
            <nav class="flex justify-between p-3 px-16">
                <nuxt-link to="/" class="h-16 w-16"><img src="~/assets/img/untangled1.png" alt="Untangled"></nuxt-link>
                <div class="flex items-center">
                    <ul class="flex justify-evenly w-56">
                        <li><nuxt-link to="/" class="font-bold text-lg hover:text-[#B8764B] duration-500 button">Home</nuxt-link></li>
                        <li><nuxt-link to="/about" class="font-bold text-lg hover:text-[#B8764B] duration-500 button">About</nuxt-link></li>
                    </ul>
                    <template v-if="!isLoggedIn">
                        <!-- Show login link if not logged in -->
                        <nuxt-link to="/login" class="p-2 font-bold text-lg text-white rounded bg-yellow-700 hover:bg-transparent hover:text-yellow-950 hover:scale-110 duration-500 button">Member Login</nuxt-link>
                    </template>
                    <template v-else>
                        <!-- Show logout link if logged in -->
                        <button @click="logout" class="p-2 font-bold text-lg text-white rounded bg-yellow-700 hover:bg-transparent hover:text-yellow-950 hover:scale-110 duration-500 button">Member Logout</button>
                    </template>
                </div>
            </nav>
        </header>

        <div class="fade-in">
            <slot />
        </div>

        <footer class="bg-[#b9b9b8] h-32 flex items-center justify-center fade-in">
            <div class="grid gap-3">
                <div>
                    &copy; {{ new Date().getFullYear() }} Untangled. All rights reserved.
                </div>
                <div class="flex justify-evenly">
                    <a href="https://www.facebook.com/klyne15">Facebook</a>
                    <a href="https://www.facebook.com/Skylir01">Twitter</a>
                    <a href="https://www.facebook.com/luis.suizo.1">Instagram</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoggedIn: false
        };
    },
    mounted() {
        // Call checkLogged initially
        this.checkLogged();

        // Set up an interval to periodically check for _token
        this.checkInterval = setInterval(() => {
            this.checkLogged();
        }, 500); // Adjust the interval time as needed
    },
    beforeDestroy() {
        // Clear the interval when the component is destroyed
        clearInterval(this.checkInterval);
    },
    methods: {
        checkLogged() {
            if (localStorage.getItem('_token')){
                this.isLoggedIn = true;
            } else {
                this.isLoggedIn = false;
            }
        },
        logout() {
            // Perform logout logic here
            // For example, remove _token from localStorage
            localStorage.removeItem('_token');
            this.isLoggedIn = false;
            navigateTo('/login');
        }
    }
};
</script>
