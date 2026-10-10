import path from 'node:path';

// Resolved from this file: the VS Code extension starts Playwright in the repo root.
export const authFile = (name: string) => path.join(__dirname, '..', '.auth', name);
