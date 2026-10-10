import React, { act } from 'react';
import { createRoot } from 'react-dom/client';
import { Simulate } from 'react-dom/test-utils';
import JournalPages from './journalPages';
import api from '../../API/api';
jest.mock('../../API/api', () => ({ getJournalPages: jest.fn(), createJournalPage: jest.fn(), updateJournalPage: jest.fn(), deleteJournalPage: jest.fn() }));
jest.mock('react-router-dom', () => ({ useParams: () => ({ slug: 'science' }), useNavigate: () => jest.fn() }));
let mockEditorContent;
jest.mock('../component/FormJoditEditor', () => props => {
 if (props.editorRef) props.editorRef.current = { get value() { return mockEditorContent ?? props.value; } };
 return <textarea aria-label="Content" name={props.name} value={props.value} onChange={props.onChange} />;
});
let container, root;
const page = { id: 1, title: 'About', slug: 'about', type: 'content', content: '<p>About science</p>', parent_id: null, sort_order: 10, is_published: true, show_in_nav: true, show_in_tabs: false, system_key: null };
const button = text => [...container.querySelectorAll('button')].find(node => node.textContent.includes(text));
beforeEach(async () => {
 global.IS_REACT_ACT_ENVIRONMENT = true; jest.clearAllMocks(); window.confirm = jest.fn(() => true);
 mockEditorContent = undefined;
 api.getJournalPages.mockResolvedValue({ data: { journal: { j_name: 'Science' }, pages: [page] } });
 container = document.createElement('div'); document.body.appendChild(container); root = createRoot(container);
 await act(async () => root.render(<JournalPages />));
});
test('saves the latest editor buffer even before a deferred change event', async () => {
 act(() => button('About').click());
 mockEditorContent = '<p>Latest unsynced edit</p>';
 api.updateJournalPage.mockResolvedValueOnce({ data: { page: { ...page, content: mockEditorContent } } });
 await act(async () => Simulate.submit(container.querySelector('form')));
 expect(api.updateJournalPage).toHaveBeenCalledWith('science', 1, expect.objectContaining({ content: '<p>Latest unsynced edit</p>' }));
});
test('creates a link with its destination instead of a content editor', async () => {
 act(() => button('+ Add page').click());
 act(() => Simulate.change(container.querySelector('#page-type'), { target: { value: 'link' } }));
 expect(container.querySelector('textarea')).toBeNull();
 expect(container.querySelector('#page-parent')).not.toBeNull();
 expect(container.querySelector('#page-show_in_tabs')).not.toBeNull();
 act(() => Simulate.change(container.querySelector('#page-link-url'), { target: { name: 'link_url', value: ' /payment ' } }));
 api.createJournalPage.mockResolvedValueOnce({ data: { page: { ...page, id: 2, type: 'link', link_url: '/payment' } } });
 await act(async () => Simulate.submit(container.querySelector('form')));
 expect(api.createJournalPage).toHaveBeenCalledWith('science', expect.objectContaining({ type: 'link', link_url: '/payment' }));
 expect(container.querySelector('#page-link-url').value).toBe('/payment');
});
afterEach(() => { act(() => root.unmount()); container.remove(); });
test('retains edited content after validation failure and supports retry', async () => {
 act(() => button('About').click());
 act(() => Simulate.change(container.querySelector('#page-title'), { target: { name: 'title', value: 'New about' } }));
 api.updateJournalPage.mockRejectedValueOnce({ response: { data: { errors: { slug: ['This slug is already in use.'] } } } });
 await act(async () => Simulate.submit(container.querySelector('form')));
 expect(container.querySelector('[role="alert"]').textContent).toContain('This slug is already in use.');
 expect(container.querySelector('#page-title').value).toBe('New about');
 expect(api.updateJournalPage).toHaveBeenCalledWith('science', 1, expect.objectContaining({ title: 'New about', sort_order: 10 }));
 api.updateJournalPage.mockResolvedValueOnce({ data: { page: { ...page, title: 'New about' } } });
 await act(async () => Simulate.submit(container.querySelector('form')));
 expect(container.querySelector('[role="status"]').textContent).toBe('Page saved.');
 expect(container.textContent).not.toContain('Unsaved changes');
});
test('preserves dirty work when discard is declined', () => {
 act(() => button('About').click());
 act(() => Simulate.change(container.querySelector('#page-title'), { target: { name: 'title', value: 'Keep me' } }));
 window.confirm.mockReturnValue(false);
 act(() => button('+ Add page').click());
 expect(container.querySelector('#page-title').value).toBe('Keep me');
 expect(window.confirm).toHaveBeenCalled();
});
test('creates root groups and deletes selected pages', async () => {
 act(() => button('+ Add page').click());
 act(() => Simulate.change(container.querySelector('#page-type'), { target: { value: 'group' } }));
 expect(container.querySelector('#page-parent')).toBeNull();
 expect(container.querySelector('#page-show_in_tabs')).toBeNull();
 api.createJournalPage.mockResolvedValueOnce({ data: { page: { ...page, id: 2, type: 'group', title: 'Policies', slug: 'policies' } } });
 await act(async () => Simulate.submit(container.querySelector('form')));
 expect(api.createJournalPage).toHaveBeenCalledWith('science', expect.objectContaining({ type: 'group', parent_id: null, show_in_tabs: false }));
 api.deleteJournalPage.mockResolvedValueOnce({ status: 204 });
 await act(async () => button('Delete page').click());
 expect(api.deleteJournalPage).toHaveBeenCalledWith('science', 2);
 expect(container.querySelector('form')).toBeNull();
});
