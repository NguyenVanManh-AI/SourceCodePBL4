<template>
  <div>
    <!-- <div id="app" class="bg-red-400 min-h-screen flex justify-center align-center flex-col"> -->
      <!-- <div class="flex justify-center space-x-5">
        <button
          class="w-54 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          @click="onClickTop"
        >Top notification</button>
        <button
          class="w-54 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          @click="onClickBot"
        >Bottom notification</button>
      </div> -->
      <notificationGroup group="top">
        <!-- <div class="fixed inset-0 flex px-4 py-6 pointer-events-none p-6 items-start justify-end"> --><!-- phía trên -->
         
        <!-- CÁCH 1 cho top về bottom khi thành công -->
          <!-- <div
          class="fixed inset-x-0 bottom-0 flex px-4 py-6 pointer-events-none p-6 items-start justify-end z-50"
        >  -->
        
        <!-- thông báo thành công nhưng ở phía dưới -->
        <!-- Thật ra cả user và admin dùng chung một file notifications có điều Thông báo thành công bị thanh header của 
        user che đi , nên phải đổi lại vị trí cho nó xuống dưới để người dùng có thể thấy thông báo -->
        <!-- chú ý một số trường hợp thông báo bị footer che đi thì tăng chiều dài của body lên để 
        cho thông báo hiện ra -->
        <!-- Thông báo hiện gần sát lề dưới nên nếu có phần nào đó của footer hiện lên thì sẽ che đi thông báo -->
        <!-- Ngoài Resetpassword ra thì tất cả đều khá dài nên không lo -->
        <!-- nếu fix thì cho div to nhất của file này z-index = 1 (chú ý đặt id khác và dài để khỏi trùng) -->

        <!-- CÁCH KHÁC HAY HƠN NHIỀU : 
        Ta có dù cho chỉnh xuống dưới thì cũng bị Footer che đi mất vì footer cũng có z-index nên
        ta cho các thông báo là z-index thật lớn ví dụ là 50 . 
        Trong framework Tailwind thì z-index : 50; là z-50 => thêm nó vào class của div to nhất cho cả thông báo Success và Error  
       -->
       <div class="fixed inset-0 flex px-4 py-6 pointer-events-none p-6 items-start justify-end z-50">
          <div class="max-w-sm w-full">
            <notification v-slot="{notifications}">
              <div
                class="flex max-w-sm w-full mx-auto bg-white shadow-md rounded-lg overflow-hidden mt-4"
                v-for="notification in notifications"
                :key="notification.id"
              >
                <div class="flex justify-center items-center w-12 bg-green-500">
                  <svg
                    class="h-6 w-6 fill-current text-white"
                    viewBox="0 0 40 40"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"
                    ></path>
                  </svg>
                </div>
  
                <div class="-mx-3 py-2 px-4">
                  <div class="mx-3">
                    <span class="text-green-500 font-semibold">{{notification.title}}</span>
                    <p class="text-gray-600 text-sm">{{notification.text}}</p>
                  </div>
                </div>
              </div>
            </notification>
          </div>
        </div>
      </notificationGroup>
      <notificationGroup group="bottom" position="bottom">
        <div
          class="fixed inset-x-0 bottom-0 flex px-4 py-6 pointer-events-none p-6 items-start justify-end z-50"
        >
          <div class="max-w-sm w-full">
            <notification v-slot="{notifications}">
              <div
                class="flex max-w-sm w-full mx-auto bg-white shadow-md rounded-lg overflow-hidden mt-4"
                v-for="notification in notifications"
                :key="notification.id"
              >
                <div class="flex justify-center items-center w-12 bg-red-500">
                  <svg
                    class="h-6 w-6 fill-current text-white"
                    viewBox="0 0 40 40"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z"
                    ></path>
                  </svg>
                </div>
  
                <div class="-mx-3 py-2 px-4">
                  <div class="mx-3">
                    <span class="text-red-500 font-semibold">{{notification.title}}</span>
                    <p class="text-gray-600 text-sm">{{notification.text}}</p>
                  </div>
                </div>
              </div>
            </notification>
          </div>
        </div>
      </notificationGroup>
    <!-- </div> -->
  </div>
  </template>
  
  <script>
import useEventBus from '../../composables/useEventBus'

  export default {
    name: "NotificationComp",
    mounted(){
      const { onEvent } = useEventBus()
      onEvent('eventUserError',(meassage)=>{
        this.$notify(
          {
            group: "bottom",
            title: "Error",
            text: meassage
          },
          4000
        );
      })
      onEvent('eventUserSuccess',(meassage)=>{
        this.$notify(
          {
            group: "top",
            title: "Success",
            text: meassage
          },
          4000
        );
      })

      onEvent('eventUserError401',(meassage)=>{
        this.$notify(
          {
            group: "bottom",
            title: "Error",
            text: meassage
          },
          4000
        );
      })

    },
    
    methods: {
      onClickTop() {
        this.$notify(
          {
            group: "top",
            title: "Success",
            text: "Your account was registered!"
          },
          4000
        );
      },
      onClickBot() {
        this.$notify(
          {
            group: "bottom",
            title: "Error",
            text: "Your email is already used!"
          },
          4000
        );
      }
    }
  };
  </script>
  
  <style>
  #app {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }
  </style>