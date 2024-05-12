<template>
  <div :key="key" v-for="{ key, message, severity } in useProperties().flash">
    <Message :life="5000" :severity="severity" :sticky="false" v-if="message">
      {{ message }}
    </Message>
  </div>
</template>
