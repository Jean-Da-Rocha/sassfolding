import type { NavigationResponse } from 'hybridly';

export type TableActionType = {
  execute: () => Promise<NavigationResponse>;
  label: string;
  metadata: Record<string, any>;
  name: string;
  style?: App.Tables.Data.ButtonStyleData;
  type: string;
};
