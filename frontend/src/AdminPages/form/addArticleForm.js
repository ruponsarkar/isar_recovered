import React from 'react';
import FormJoditEditor from '../component/FormJoditEditor';
import AuthorText from '../../components/common/AuthorText';
import './addArticleForm.css';

const AddArticleForm = (props) => {
    const authors = props.articleData.authors || [];
    const updateAuthors = (value) => props.handleAddInput({ target: { name: 'authors', value } });
    const addAuthor = () => updateAuthors([...authors, { first_name: '', last_name: '', designation: '', affiliation: '', corresponding: false, super_no: authors.length ? Math.max(...authors.map(a => Number(a.super_no) || 0)) + 1 : 1 }]);
    const changeAuthor = (index, field, value) => updateAuthors(authors.map((author, i) => i === index ? { ...author, [field]: value } : author));
    const field = (name, label, type = 'text') => (
        <div className="article-field" key={name}>
            <label htmlFor={`article-${name}`}>{label}</label>
            <input id={`article-${name}`} type={type} name={name} onChange={props.handleAddInput} value={props.articleData[name] || ''} className="form-control" />
        </div>
    );

    return (
        <div className="article-editor-form">
            {field('name', 'Article Title')}
            <section className="article-authors" aria-labelledby="article-authors-heading">
                <div className="article-authors-heading">
                    <h3 id="article-authors-heading">Authors</h3>
                    <span>{authors.length} {authors.length === 1 ? 'author' : 'authors'}</span>
                </div>
                <p className="article-form-help">Add each author separately. Author names and affiliations are generated when saved.</p>
                {authors.length === 0 && (
                    <div className="article-author-empty">
                        {props.articleData.aname ? <>
                            <p><strong>Existing authors</strong><br /><AuthorText value={props.articleData.aname} /></p>
                            {props.articleData.designation && <p><strong>Existing affiliations</strong><br /><AuthorText value={props.articleData.designation} /></p>}
                            <p className="article-form-help">These details are retained unless you add individual author records below.</p>
                        </> : <p>No authors added yet. Add an author to enter their details.</p>}
                    </div>
                )}
                {authors.map((author, index) => (
                    <fieldset className="article-author-card" key={index}>
                        <legend>Author {index + 1}</legend>
                        <div className="article-author-grid">
                            {['first_name', 'last_name', 'designation'].map(name => (
                                <div className="article-field" key={name}>
                                    <label htmlFor={`author-${index}-${name}`}>{name === 'first_name' ? 'First Name' : name === 'last_name' ? 'Last Name' : 'Designation'}</label>
                                    <input id={`author-${index}-${name}`} className="form-control" value={author[name] || ''} onChange={e => changeAuthor(index, name, e.target.value)} />
                                </div>
                            ))}
                            <div className="article-field">
                                <label htmlFor={`author-${index}-super_no`}>Super No.</label>
                                <input id={`author-${index}-super_no`} className="form-control" type="number" min="1" max="9999" step="1" value={author.super_no ?? ''} onChange={e => changeAuthor(index, 'super_no', e.target.value)} />
                            </div>
                            <div className="article-field article-field-full">
                                <label htmlFor={`author-${index}-affiliation`}>Affiliation</label>
                                <textarea id={`author-${index}-affiliation`} rows="2" className="form-control" value={author.affiliation || ''} onChange={e => changeAuthor(index, 'affiliation', e.target.value)} />
                            </div>
                        </div>
                        <div className="article-author-actions">
                            <label className="article-corresponding" htmlFor={`author-${index}-corresponding`}>
                                <input id={`author-${index}-corresponding`} type="checkbox" checked={Boolean(author.corresponding)} onChange={e => changeAuthor(index, 'corresponding', e.target.checked)} />
                                Corresponding author
                            </label>
                            <button type="button" className="btn btn-outline-danger btn-sm" aria-label={`Remove author ${index + 1}`} onClick={() => updateAuthors(authors.filter((_, i) => i !== index))}>Remove</button>
                        </div>
                    </fieldset>
                ))}
                <button type="button" className="btn btn-primary article-add-author" onClick={addAuthor}>+ Add More Author</button>
            </section>
            {field('googleScholar', 'Google Scholar')}
            <div className="article-metadata-grid">
                {field('doi', 'DOI')}
                {field('page', 'Page')}
                {field('published_date', 'Published Date', 'date')}
            </div>
            <div className="article-metadata-grid">
                {['received', 'revised', 'accepted'].map(name => field(name, name.charAt(0).toUpperCase() + name.slice(1), 'date'))}
            </div>
            <div className="article-field">
                <label htmlFor="article-keywords">Keywords</label>
                <textarea id="article-keywords" rows="3" name="keywords" className="form-control" value={props.articleData.keywords || ''} onChange={props.handleAddInput} />
            </div>
            <div className="article-field">
                <label>Abstract</label>
                <FormJoditEditor name="abstract" value={props.articleData.abstract || ''} onChange={props.handleAddInput} />
            </div>
            <div className="article-field">
                <label htmlFor="article-file">File</label>
                <input id="article-file" type="file" onChange={props.handleFile} className="form-control" />
            </div>
        </div>
    );
};

export default AddArticleForm;
