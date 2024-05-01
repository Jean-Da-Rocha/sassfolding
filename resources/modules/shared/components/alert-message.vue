<template>
  <div :key="severity" v-for="(message, severity) in useProperties().flash">
    <Message :life="5000" :severity="severity.toString()" :sticky="false" v-if="message">
      {{ message }}
    </Message>
  </div>
</template>
