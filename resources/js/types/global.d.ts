import { Page } from '@inertiajs/core';

declare global {
    function route(name: string, params?: any, absolute?: boolean): string;
    function lang(): any;
    function can(permissions: string[]): boolean;
}

declare module '@inertiajs/core' {
    interface PageProps {
        app: {
            perpage: number[];
        };
    }
}
