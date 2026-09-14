import React from 'react'
import Card from '@mui/material/Card';

import { Divider } from '@mui/material';

const AuthorInstruction = () => {
    return (
        <>
            <Card>
                <div className='p-4'>
                    <h3 className='text-uppercase'>Instruction to Authors </h3>
                </div>
            </Card>
            <Divider />
            <Card>
                <div className="row">
                    <div className="col-12 p-4">



                        <div className="col-12 m-2">
                            <p>

                                The process for preparing a manuscript for submission to the journal is outlined as follows:
                            </p>
                            <p>

                               <strong> Page Format: </strong>
                                <ul><li>
                                    Manuscripts should be submitted in English or according to the journal's criteria.
                                </li>
                                    <li>

                                        Format the document as a Microsoft Word file.
                                    </li>
                                    <li>

                                        Use Times New Roman font with a size of 10.
                                    </li>
                                    <li>

                                        Set the page size to A4.
                                    </li>
                                    <li>

                                        The text must be within 30 printable pages.
                                    </li>
                                    <li>

                                        Maintain 1-inch margins on all four sides.
                                    </li>
                                    <li>

                                        Use a single-column layout.
                                    </li>
                                    <li>

                                        Ensure single-spaced line spacing throughout the document, including references and tables.
                                    </li>
                                </ul>
                            </p>
                            <p>
                                <strong> Manuscript Structure: </strong> The manuscript should be organized using the following headings:
                                <ul>
                                    <li>

                                        Title of Manuscript: A concise and descriptive title.
                                    </li>
                                    <li>

                                        Author Name(s): List the full names of all authors along with their affiliations.
                                    </li>
                                    <li>

                                        Corresponding Author: Provide the name and email address of the corresponding author.
                                    </li>
                                    <li>

                                        Abstract: A single-paragraph summary of the work (maximum 300 words), without subheadings.
                                    </li>
                                    <li>

                                        Keywords: Include up to 7 keywords, especially for unfamiliar or lengthy terms.
                                    </li>
                                    <li>

                                        Introduction: Clearly state the current issues or context.
                                    </li>
                                    <li>

                                        Materials and Methods: A clear and detailed explanation of the research methods.
                                    </li>
                                    <li>

                                        Results and Discussion: Present research findings, interpretations, speculations, and future prospects.
                                    </li>
                                    <li>

                                        Conclusion: Summarize the work's outcomes, significant findings, and the author's perspective.
                                    </li>
                                    <li>

                                        Acknowledgment (Optional): Include brief information about grants and funding sources.
                                    </li>
                                    <li>

                                        References: Cite references within the text using square brackets, e.g., (Anju B, 2018) for a single author or (Anju B et al., 2018) for multiple authors. Arrange references at the end of the manuscript following the APA Citation Style.
                                    </li>
                                </ul>
                            </p>
                            <p>

                               <strong> Author Information:</strong> Provide complete author information, including full names, affiliations, email addresses, and phone numbers. Designate the corresponding author.
                            </p>
                            <p>

                               <strong> Abstract: </strong>Compose an abstract that summarizes the work within a single paragraph of no more than 300 words. Avoid subheadings and keep it concise.
                            </p>
                            <p>

                               <strong> Keywords: </strong>Select up to 7 keywords that help readers understand the essential concepts in your manuscript, particularly for complex or less common terms.
                            </p>
                            <p>

                               <strong> Introduction: </strong>Clearly articulate the current issues or context relevant to the research.
                            </p>
                            <p>

                               <strong> Materials and Methods: </strong>Thoroughly explain the research methods used in your study to ensure clarity and reproducibility.
                            </p>
                            <p>

                               <strong> Results and Discussion:</strong> Present research results, interpretations, speculations, and prospects for future research or developments.
                            </p>
                            <p>

                               <strong> Conclusion: </strong>Summarize the outcomes of your work, highlight significant findings, and share your viewpoint.
                            </p>
                            <p>

                               <strong> Acknowledgment (Optional): </strong>Include brief acknowledgments, grant details, and funding information if applicable.
                            </p>
                            <p>

                               <strong> References: </strong>Cite references in square brackets within the text. List references at the end of the manuscript following the APA Citation Style.
                            </p>
                            <p>

                               Adhering to  these guidelines will help authors prepare their manuscripts for submission to the journal effectively and efficiently, ensuring clarity, consistency, and adherence to formatting requirements.
                            </p>


                        </div>


                    </div>
                </div>
            </Card>
            <br />
        </>
    )
}

export default AuthorInstruction