import type * as _hybridly_core from '@hybridly/core';

export type TableActionType = {
  execute: () => Promise<_hybridly_core.NavigationResponse>;
  label: string;
  metadata: App.Tables.Data.ActionData;
  type: string;
  name: string;
};
