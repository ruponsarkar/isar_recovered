import React, { useEffect, useRef, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import api from '../../API/api';
import FormJoditEditor from '../component/FormJoditEditor';
import './journalPages.css';

const emptyPage = () => ({ title: '', slug: '', type: 'content', system_key: null, content: '', link_url: '', parent_id: null, sort_order: 0, is_published: false, show_in_nav: true, show_in_tabs: false });
const fields = page => ({ ...emptyPage(), ...page, content: page.content || '', link_url: page.link_url || '', is_published: !!page.is_published, show_in_nav: !!page.show_in_nav, show_in_tabs: !!page.show_in_tabs });
const errorMessage = error => Object.values(error.response?.data?.errors || {}).flat().join(' ') || error.response?.data?.message || 'Unable to complete this request. Please try again.';

export default function JournalPages() {
    const { slug } = useParams();
    const navigate = useNavigate();
    const [journal, setJournal] = useState(null);
    const [pages, setPages] = useState([]);
    const [form, setForm] = useState(null);
    const [saved, setSaved] = useState('');
    const [loading, setLoading] = useState(true);
    const [busy, setBusy] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [reload, setReload] = useState(0);
    const contentEditor = useRef(null);
    const dirty = !!form && JSON.stringify(form) !== saved;
    const dirtyRef = useRef(dirty);
    dirtyRef.current = dirty;
    useEffect(() => {
        let active = true;
        setLoading(true); setError(''); setForm(null);
        api.getJournalPages(slug).then(({ data }) => {
            if (!active) return;
            setJournal(data.journal); setPages(data.pages);
        }).catch(e => { if (active) setError(errorMessage(e)); })
            .finally(() => { if (active) setLoading(false); });
        return () => { active = false; };
    }, [slug, reload]);
    useEffect(() => {
        const warn = event => { if (dirtyRef.current) { event.preventDefault(); event.returnValue = ''; } };
        const guardLink = event => {
            if (dirtyRef.current && event.target.closest('a[href]') && !window.confirm('Discard unsaved page changes?')) {
                event.preventDefault(); event.stopPropagation();
            }
        };
        window.addEventListener('beforeunload', warn);
        document.addEventListener('click', guardLink, true);
        return () => { window.removeEventListener('beforeunload', warn); document.removeEventListener('click', guardLink, true); };
    }, []);
    const canLeave = () => !dirty || window.confirm('Discard unsaved page changes?');
    const select = page => {
        if (busy || !canLeave()) return;
        const next = fields(page);
        setForm(next); setSaved(JSON.stringify(next)); setError(''); setSuccess('');
    };
    const update = event => {
        const { name, value, type, checked } = event.target;
        setForm(previous => ({ ...previous, [name]: type === 'checkbox' ? checked : value }));
    };
    const changeType = event => {
        const type = event.target.value;
        setForm(previous => ({ ...previous, type, system_key: type === 'system' ? 'current_issue' : null, ...(type === 'group' ? { parent_id: null, show_in_tabs: false } : {}) }));
    };
    const save = async event => {
        event.preventDefault();
        if (busy) return;
        setBusy(true); setError(''); setSuccess('');
        const { id, journal_id, created_at, updated_at, ...payload } = form;
        payload.sort_order = Number(payload.sort_order);
        payload.parent_id = payload.parent_id ? Number(payload.parent_id) : null;
        payload.link_url = form.type === 'link' ? form.link_url.trim() : null;
        // Read the live editor before a busy-state rerender; Jodit change events may be deferred.
        if (form.type === 'content' && contentEditor.current) {
            payload.content = contentEditor.current.value;
            setForm(previous => ({ ...previous, content: payload.content }));
        }
        try {
            const { data } = id ? await api.updateJournalPage(slug, id, payload) : await api.createJournalPage(slug, payload);
            const next = fields(data.page);
            setPages(previous => id ? previous.map(page => page.id === id ? data.page : page) : [...previous, data.page]);
            setForm(next); setSaved(JSON.stringify(next)); setSuccess('Page saved.');
        } catch (e) { setError(errorMessage(e)); }
        finally { setBusy(false); }
    };
    const remove = async () => {
        if (busy || !window.confirm(`Delete “${form.title}”?${dirty ? ' Unsaved changes will also be discarded.' : ''}`)) return;
        setBusy(true); setError(''); setSuccess('');
        try {
            await api.deleteJournalPage(slug, form.id);
            setPages(previous => previous.filter(page => page.id !== form.id));
            setForm(null); setSaved(''); setSuccess('Page deleted.');
        } catch (e) { setError(errorMessage(e)); }
        finally { setBusy(false); }
    };
    const groups = pages.filter(page => page.type === 'group' && !page.parent_id && page.id !== form?.id);
    const ordered = [...pages].sort((a, b) => a.sort_order - b.sort_order || a.id - b.id);
    return <section className="journal-pages p-3 p-md-4">
        <button className="btn btn-link p-0 mb-3" disabled={busy} onClick={() => { if (canLeave()) navigate('/admin/viewjournal'); }}>← All journals</button>
        <header className="mb-4"><div className="text-muted small">JOURNAL SETTINGS</div><h2>Pages &amp; navigation</h2><p className="mb-1">{journal?.j_name || 'Journal pages'}</p><p className="text-muted mb-0">Manage this journal’s content and menus. Home page navigation stays unchanged.</p></header>
        {error && <div role="alert" className="alert alert-danger">{error}{!journal && !loading && <button className="btn btn-outline-danger ms-2" onClick={() => setReload(n => n + 1)}>Retry</button>}</div>}
        {success && <div role="status" className="alert alert-success">{success}</div>}
        {loading ? <p role="status">Loading journal pages…</p> : journal && <div className="journal-pages-layout">
            <aside className="journal-pages-list" aria-label="Journal pages">
                <div className="d-flex align-items-center justify-content-between mb-3"><h3 className="h5 mb-0">Pages <span className="text-muted">({pages.length})</span></h3><button className="btn btn-primary btn-sm" disabled={busy} onClick={() => select(emptyPage())}>+ Add page</button></div>
                {!pages.length && <p className="text-muted">No pages yet. Add a page or a menu group to get started.</p>}
                <ul className="list-unstyled mb-0">{ordered.map(page => <li key={page.id}><button type="button" aria-pressed={form?.id === page.id} disabled={busy} className={`journal-page-choice ${form?.id === page.id ? 'selected' : ''}`} onClick={() => select(page)}>
                    <span className="d-block fw-semibold">{page.title}</span>
                    <span className="d-flex flex-wrap gap-2 small mt-1"><span className={page.is_published ? 'text-success' : 'text-secondary'}>{page.is_published ? 'Published' : 'Draft'}</span><span>{page.type === 'group' ? 'Menu group' : page.type === 'system' ? 'System section' : page.type === 'link' ? 'Link' : 'Content page'}</span></span>
                    <span className="d-block small text-muted mt-1">Order {page.sort_order} · {page.show_in_nav ? 'Navbar' : 'Not in navbar'}{page.show_in_tabs ? ' · Journal tabs' : ''}{page.parent_id ? ` · ${pages.find(parent => parent.id === page.parent_id)?.title || 'Group'}` : ''}</span>
                </button></li>)}</ul>
            </aside>
            <div className="journal-pages-editor">
                {!form ? <div className="py-5 text-center"><h3 className="h5">Choose a page to edit</h3><p className="text-muted">Or add a new page to build this journal’s navigation.</p></div> : <form onSubmit={save}>
                    <div className="d-flex justify-content-between align-items-center mb-3"><h3 className="h5 mb-0">{form.id ? 'Edit page' : 'New page'}</h3>{dirty && <span className="small text-warning-emphasis">Unsaved changes</span>}</div>
                    <fieldset disabled={busy}>
                        <div className="row g-3">
                            <div className="col-md-6"><label htmlFor="page-title" className="form-label">Page title</label><input id="page-title" className="form-control" name="title" maxLength={150} required value={form.title} onChange={update} /></div>
                            <div className="col-md-6"><label htmlFor="page-slug" className="form-label">URL slug</label><input id="page-slug" className="form-control" name="slug" maxLength={100} pattern="[a-z0-9]+(-[a-z0-9]+)*" required value={form.slug} onChange={update} /><small className="text-muted">Lowercase words separated by hyphens.</small></div>
                            <div className="col-md-6"><label htmlFor="page-type" className="form-label">Page type</label><select id="page-type" className="form-select" value={form.type} onChange={changeType}><option value="content">Content page</option><option value="group">Menu group</option><option value="system">System section</option><option value="link">Link (external URL or internal path)</option></select></div>
                            <div className="col-md-6"><label htmlFor="page-order" className="form-label">Menu order</label><input id="page-order" className="form-control" type="number" min="0" max="100000" step="1" required name="sort_order" value={form.sort_order} onChange={update} /></div>
                            {form.type !== 'group' && <div className="col-md-6"><label htmlFor="page-parent" className="form-label">Menu group</label><select id="page-parent" name="parent_id" className="form-select" value={form.parent_id || ''} onChange={update}><option value="">None — top level</option>{groups.map(group => <option key={group.id} value={group.id}>{group.title}</option>)}</select></div>}
                            {form.type === 'link' && <div className="col-12"><label htmlFor="page-link-url" className="form-label">Destination URL</label><input id="page-link-url" name="link_url" className="form-control" type="text" maxLength={2048} required value={form.link_url} onChange={update} placeholder="https://www.google.com or /Payment" /><small className="text-muted">Use a complete https:// or http:// address, or a path starting with /. Opens in the same tab. Internal paths are case-sensitive.</small></div>}
                            {form.type === 'system' && <div className="col-md-6"><label htmlFor="page-system" className="form-label">System section</label><select id="page-system" name="system_key" className="form-select" value={form.system_key || 'current_issue'} onChange={update}><option value="current_issue">Current issue</option><option value="archive">Archive</option><option value="editorial_board">Editorial board</option></select></div>}
                        </div>
                        <div className="journal-page-visibility my-4">{[['is_published', 'Published'], ['show_in_nav', 'Show in navbar'], ...(form.type === 'group' ? [] : [['show_in_tabs', 'Show in journal tabs']])].map(([name, label]) => <div className="form-check" key={name}><input id={`page-${name}`} className="form-check-input" type="checkbox" name={name} checked={form[name]} onChange={update} /><label className="form-check-label" htmlFor={`page-${name}`}>{label}</label></div>)}<p className="small text-muted mb-0 mt-2">Drafts are private. Published pages hidden from menus can still be opened by their direct link.</p></div>
                        {form.type === 'content' ? <div><label className="form-label" id="page-content-label">Page content</label><div role="group" aria-labelledby="page-content-label"><FormJoditEditor name="content" value={form.content} onChange={update} editorRef={contentEditor} readonly={busy} /></div></div> : <p className="alert alert-light border">{form.type === 'link' ? 'Clicking this item in the navbar or journal tabs opens its destination directly.' : form.type === 'group' ? 'A menu group holds pages in a dropdown. Move or delete its child pages before deleting or changing this group.' : 'This section uses the journal’s existing issue or editorial board records. Manage those records in their existing admin screens.'}</p>}
                    </fieldset>
                    <div className="d-flex flex-wrap gap-2 mt-4 align-items-center"><button className="btn btn-primary" type="submit" disabled={busy}>{busy ? 'Working…' : 'Save page'}</button><button className="btn btn-outline-secondary" type="button" disabled={busy} onClick={() => { if (canLeave()) { setForm(null); setError(''); } }}>Cancel</button>{form.id && <button className="btn btn-outline-danger ms-auto" type="button" disabled={busy} onClick={remove}>Delete page</button>}</div>
                </form>}
            </div>
        </div>}
    </section>;
}
