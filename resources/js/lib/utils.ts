import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import {InertiaLinkProps} from "@inertiajs/vue3";

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function urlIsActive(
    urlToCheck: NonNullable<InertiaLinkProps['href']>,
    currentUrl: string,
) {
    const target = toUrl(urlToCheck);
    const currentPath = currentUrl.split('?')[0];

    return (
        currentPath === target ||
        currentPath.startsWith(target + '/')
    );
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}