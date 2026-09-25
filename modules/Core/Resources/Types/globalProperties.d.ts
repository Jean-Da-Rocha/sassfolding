import 'hybridly';

// Hybridly stopped generating this declaration in the 0.10 beta line. Without it, `useProperty`
// resolves its keys against an empty interface and every call fails to type check.
declare module 'hybridly' {
  // eslint-disable-next-line ts/consistent-type-definitions
  export interface GlobalHybridlyProperties extends Modules.Core.Data.SharedData {}
}

export {};
