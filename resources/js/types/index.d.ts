import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Parent {
    id: number,
    name: string
}

export interface downloadFinishedPayload  {
    content: Content,
    url: string,
    size: float
}

export interface Content {
    id: number;
    title: string;
    thumbnail_url: string;
    url: string;
    type: string;
    downloaded: boolean;
    is_favorite: boolean;
    local_url?: string;
    parent: Parent,
    name?: string,
    size?: number
    player_type: string
}

export interface downloadOutput {
    downloadOutput: {
        percent: number,
        total: number,
        content: Content,
    }
}

export interface Series {
    id: number;
    name: string;
    thumbnail: string;
    category: number;
    author: string;
    downloaded: boolean;
    downloadable_content: Content[]
}

export interface AppSettings {
    content_per_page: number;
    download_path: string;
    video_quality: number;
    video_player_path: string;
    book_reader_path: string;
}

export interface UnfavoriteEvent {
    content: Content;
    contentEntity: ContentEntity
}

export interface JobRequest {
    id: number,
    job_status: 'queued' | 'done' | 'in_progress' | 'cancelled' | 'failed',
    jobType: string
    content: Content
    size?: number
    job_reference: Content
    job_reference_id: number
    reference: Content
    progress?: number
}

export interface Link {
    url: string,
    label: string,
    active: boolean
}
export interface Category {
    id: number,
    name: string,
    size: number
}

export type FileType = 'file'|'folder'

export type ContentEntity = 'series'|'content'
export type AppMode = 'online'|'offline'
export type AppModeSwitch = 'manual'|'automatic'

export type BreadcrumbItemType = BreadcrumbItem;
