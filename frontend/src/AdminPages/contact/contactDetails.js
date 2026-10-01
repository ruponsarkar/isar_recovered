import React, { useEffect, useState } from 'react';
import api from '../../API/api';
import './contactDetails.css';

const fields = value => ({ address: value?.address || '', email: value?.email || '', phone: value?.phone || '' });
const errorMessage = error => Object.values(error.response?.data?.errors || {}).flat().join(' ') || error.response?.data?.message || 'Unable to load or save contact details. Please try again.';

export default function ContactDetails() {
    const [scope, setScope] = useState('default');
    const [form, setForm] = useState(null);
    const [saved, setSaved] = useState('');
    const [defaults, setDefaults] = useState(fields());
    const [journals, setJournals] = useState([]);
    const [loading, setLoading] = useState(true);
    const [busy, setBusy] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [reload, setReload] = useState(0);
    const isDefault = scope === 'default';
    const dirty = !!form && JSON.stringify(form) !== saved;

    const receive = data => {
        const next = fields(data.contact);
        setForm(next); setSaved(JSON.stringify(next));
        setDefaults(fields(data.defaults)); setJournals(data.journals || []);
    };
    useEffect(() => {
        let active = true;
        setLoading(true); setForm(null); setError(''); setSuccess('');
        api.getContactDetails(scope).then(({ data }) => { if (active) receive(data); })
            .catch(e => { if (active) setError(errorMessage(e)); })
            .finally(() => { if (active) setLoading(false); });
        return () => { active = false; };
    }, [scope, reload]);
    useEffect(() => {
        const warn = event => { if (dirty) { event.preventDefault(); event.returnValue = ''; } };
        window.addEventListener('beforeunload', warn);
        return () => window.removeEventListener('beforeunload', warn);
    }, [dirty]);
    const update = event => {
        const { name, value } = event.target;
        setForm(previous => ({ ...previous, [name]: value })); setSuccess('');
    };
    const save = async event => {
        event.preventDefault();
        if (busy) return;
        setBusy(true); setError(''); setSuccess('');
        try {
            const payload = Object.fromEntries(Object.entries(form).map(([key, value]) => [key, value.trim()]));
            if (isDefault && Object.values(payload).some(value => !value)) {
                setError('Enter an address, email and phone for the default contact.'); return;
            }
            const { data } = await api.updateContactDetails(scope, payload);
            receive(data); setSuccess('Contact details saved.');
        } catch (e) { setError(errorMessage(e)); }
        finally { setBusy(false); }
    };
    const reset = async () => {
        if (busy || !window.confirm('Use the default contact for this journal? This removes its contact overrides and any unsaved changes.')) return;
        setBusy(true); setError(''); setSuccess('');
        try {
            const { data } = await api.resetContactDetails(scope);
            receive(data); setSuccess('This journal now uses the default contact.');
        } catch (e) { setError(errorMessage(e)); }
        finally { setBusy(false); }
    };
    const journalName = journals.find(journal => String(journal.j_id) === scope)?.j_name || 'Journal';

    return <section className="contact-settings p-3 p-md-4">
        <header className="mb-4"><div className="small text-muted">SITE SETTINGS</div><h2>Contact details</h2><p className="text-muted mb-0">Manage the address, email and phone shown in the footer and on Contact Us.</p></header>
        <div className="contact-scope mb-4">
            <label htmlFor="contact-scope" className="form-label fw-semibold">Contact for</label>
            <select id="contact-scope" className="form-select" value={scope} disabled={busy || loading} onChange={event => {
                if (!dirty || window.confirm('Discard unsaved contact changes?')) setScope(event.target.value);
            }}>
                <option value="default">Default contact · Home &amp; fallback</option>
                {journals.map(journal => <option key={journal.j_id} value={journal.j_id}>{journal.j_name}</option>)}
            </select>
            <p className="small text-muted mt-2 mb-0">{isDefault ? 'Shown on the home page and used wherever a journal has no contact override.' : 'Leave any field blank to use the default contact for that field.'}</p>
        </div>
        {error && <div role="alert" className="alert alert-danger">{error}{!form && !loading && <button className="btn btn-outline-danger ms-2" onClick={() => setReload(value => value + 1)}>Retry</button>}</div>}
        {success && <div role="status" className="alert alert-success">{success}</div>}
        {loading ? <p role="status">Loading contact details…</p> : form && <div className="contact-settings-layout">
            <form className="contact-editor" onSubmit={save}>
                <div className="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3"><h3 className="h5 mb-0">{isDefault ? 'Default contact' : journalName}</h3>{dirty && <span className="small text-warning-emphasis">Unsaved changes</span>}</div>
                <fieldset disabled={busy}>
                    <div className="mb-3"><label className="form-label" htmlFor="contact-address">Address{!isDefault && ' (optional)'}</label><textarea className="form-control" id="contact-address" name="address" rows={5} maxLength={2000} required={isDefault} value={form.address} onChange={update} placeholder={isDefault ? 'Publisher mailing address' : defaults.address} /></div>
                    <div className="mb-3"><label className="form-label" htmlFor="contact-email">Email{!isDefault && ' (optional)'}</label><input className="form-control" id="contact-email" name="email" type="email" maxLength={255} required={isDefault} value={form.email} onChange={update} placeholder={isDefault ? 'contact@example.com' : defaults.email} /></div>
                    <div className="mb-3"><label className="form-label" htmlFor="contact-phone">Phone{!isDefault && ' (optional)'}</label><input className="form-control" id="contact-phone" name="phone" type="tel" maxLength={80} pattern="[+0-9\(\).\s\-]+" aria-describedby="contact-phone-hint" required={isDefault} value={form.phone} onChange={update} placeholder={isDefault ? 'Phone number with country code' : defaults.phone} /><small id="contact-phone-hint" className="text-muted">Use numbers, spaces, +, parentheses, dots or hyphens.</small></div>
                    <div className="d-flex flex-wrap gap-2 mt-4"><button className="btn btn-primary" type="submit">{busy ? 'Saving…' : 'Save contact details'}</button>{!isDefault && <button className="btn btn-outline-secondary" type="button" onClick={reset}>Use default contact</button>}</div>
                </fieldset>
            </form>
            <aside className="contact-preview" aria-label="Contact preview">
                <div className="small contact-preview-eyebrow">FOOTER &amp; CONTACT US</div><h3 className="h5 mt-2">Contact preview</h3>
                <p className="small text-muted">{isDefault ? 'The shared contact for your website.' : `Contact visitors will see for ${journalName}.`} Preview includes unsaved edits.</p>
                <dl className="mb-0">{[['address', 'Address'], ['email', 'Email'], ['phone', 'Phone']].map(([key, label]) => {
                    const inherited = !isDefault && !form[key].trim();
                    const value = inherited ? defaults[key] : form[key].trim();
                    return <React.Fragment key={key}><dt className="small mt-4">{label}{inherited && <span className="contact-inherited ms-2">Default</span>}</dt><dd className="mt-1" data-contact-preview={key}>{value || 'Not provided'}</dd></React.Fragment>;
                })}</dl>
            </aside>
        </div>}
    </section>;
}
