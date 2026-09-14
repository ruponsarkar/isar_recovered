import React, { useContext, useEffect, useState } from "react";
import { Card } from "@mui/material";
import { Divider } from "@mui/material";
import { JournalContext } from "../../Helper/Contex";
import GenerativeAIPolicy from "./GenerativeAIPolicy";
import { Link } from "react-router-dom";

const AboutUs = () => {
    const { journals, indexings, topEditors } = useContext(JournalContext);
    return (
        <>
            <Card>
                <div className="m-2">
                    <h3> About Us </h3>
                </div>
            </Card>
            <Divider />

            <Card>
                <div className="m-2">
                    <p>
                        International Scientific and Academic Research Publisher is an
                        international publisher committed to providing a ‘peer reviewed’
                        platform to outstanding researchers and scientists to exhibit their
                        findings for the furtherance of society to provoke debate and
                        provide an educational forum.
                    </p>
                    <p>
                        It's aim is to serve for the scholars and researchers all over the
                        world by means of fast publishing and to get the widest exposure by
                        providing free access. International Scientific and Academic
                        Research Publishers team works with full dedication to improve the
                        services and to maintain high quality standards.
                    </p>
                </div>

                <div className="m-2">
                    <Card>
                        <div className="m-2">
                            <h4> Our Journal List </h4>
                        </div>
                    </Card>

                    <div>
                        <div className="row">
                            {journals &&
                                journals.map((j) => (
                                    <div className="col-12 border border-primary">
                                        <div className="m-3">
                                            <div>
                                                <b>
                                                    <Link to={`/journal/${j.slug}`}>
                                                        {j.j_name}{" "}
                                                    </Link>{" "}
                                                </b>
                                            </div>
                                            <div>
                                                <b> Journal starting year: </b>
                                                {j.starting_year}
                                            </div>
                                            <div>
                                                <b> Frequency: </b>
                                                {j.frequency}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                        </div>
                    </div>
                </div>
            </Card>

            <Card>
                <div className="m-2">
                    <Card>
                        <div className="m-2">
                            <h4> Policies </h4>
                        </div>
                    </Card>

                    <div className="m-2">
                        <p>
                            <b> <a href="/author-instruction"> Guidelines to Authors:</a> </b> Go through our guidelines for
                            authors before submitting your manuscript that will assist in
                            preparing your manuscript as per format of the journal.
                        </p>

                        <p>
                            <b> <a href="/EthicsPolicies">Ethics & Policies:</a> </b> The guidelines that are developed to
                            ensure world class scientific publication and proper credit for
                            author's original ideas and research.
                        </p>

                        <p>
                            <b><a href="/reviewProcess">Peer Review Policy:</a> </b> All submitted manuscripts undergo a
                            rigorous peer-review process by at least two independent reviewers
                            before acceptance.
                        </p>

                        <p>
                            <b> <a href="/EditorGuidlines"> Editorial Policies:</a></b> Role of Editors ensure the quality of
                            the journal by making fruitful relations with author, reviewers
                            and adhere to high value responsibilities towards other board
                            members.
                        </p>

                        <p>
                            <b><a href="/CopyrightPolicyPage"> Copyright Policy:</a> </b> All manuscripts are published under Open
                            Access, allowing immediate and free full-text access upon
                            publication under the Creative Commons Attribution-NonCommercial
                            4.0 International License.
                        </p>

                        <p>
                            <b> <a href="/PublicationFee"> Publication Fee: </a> </b> Article Processing Charges (APC) cover the
                            costs of peer review, editorial handling, production, hosting,
                            distribution, indexing, and promotion required to transform a
                            submitted manuscript into a published article.
                        </p>

                        <p>
                            <b> <a href="/open-access-policy">Open Access Policy:</a></b> ISAR Publisher bridges the gap between
                            research and communication by providing open access to all
                            journals, empowering the global research community through
                            unrestricted, accessible, and inclusive scholarly content.
                        </p>

                        <p>
                            <b> <a href="/refundpolicy">Cancellation/Refund Policy:</a></b> ISAR Publisher follows a clear
                            policy for article correction, retraction, or removal to maintain
                            academic integrity, transparency, and accuracy, in accordance with
                            established publishing ethics guidelines.
                        </p>

                        <p>
                            <b> <a href="/GenerativeAIPolicy">Generative AI Policy:</a></b> The Use of Generative AI and AIAssisted Technologies in Writing for ISAR Publisher
                        </p>

                        {/* <p>
                            <h4>Generative AI Policy</h4>
                        </p> */}

                        {/* <GenerativeAIPolicy /> */}
                        {/* <p>
                            <h4>The Use of Generative AI and AIAssisted Technologies in Writing for ISAR Publisher</h4>
                        </p>

                        <p>
                            <b>Background and Rationale</b>
                            <div>
                                Generative Artificial Intelligence (GenAI) presents both opportunities and challenges in scientific writing and academic publishing. To enhance transparency, research integrity, and trust in scholarly communication, ISAR Publisher has established this policy governing the responsible use of GenAI and AIassisted technologies. This policy aligns with recommendations from major indexing bodies and publishing standards, including Scopus, COPE, and leading international publishers.
                            </div>

                            <div>
                                ISAR Publisher recognizes that GenAI technologies are evolving rapidly. Therefore, this policy focuses on principles of transparency, accountability, and ethical use rather than prescribing specific tools or rigid technical requirements.
                            </div>

                            <div>
                                <b>Scope of the Policy</b>
                            </div>

                            <div>
                                This policy applies to:
                                <ul>
                                    <li>
                                        Authors submitting manuscripts to ISAR Publisher journals
                                    </li>
                                    <li>
                                        Editors, editorial board members, and publishing staff

                                    </li>
                                    <li>
                                        Peer reviewers involved in the editorial and peer review process
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <b>Definition of Generative AI and AIAssisted Technologies</b>
                            </div>
                            <div>
                                Generative AI and AIassisted technologies refer to software or systems capable of generating, editing, summarizing, translating, or enhancing text, images, or other content based on prompts or data inputs. Examples include, but are not limited to, large language models, AIbased language editing tools, and automated contentgeneration systems.
                            </div>

                            <div>
                                <b>Acceptable Use by Authors</b>
                            </div>
                            <div>
                                Authors may use GenAI or AIassisted tools for limited and supportive purposes, such as:
                                <ul>
                                    <li>Improving grammar, spelling, clarity, and language quality</li>
                                    <li>Enhancing readability and structure</li>
                                    <li>Assisting with formatting or reference management</li>
                                </ul>
                            </div>
                            <div>
                                Conditions:
                                <ul>
                                    <li>GenAI tools must not replace the authors’ original intellectual contribution, analysis, or scholarly judgment</li>
                                    <li>
                                        Authors must critically review, verify, and edit all AIassisted content
                                    </li>
                                    <li>
                                        Authors remain fully responsible for the accuracy, originality, and integrity of the manuscript
                                    </li>
                                </ul>
                            </div>


                            <div>
                                <b>Prohibited Use</b>
                            </div>
                            <div>
                                The following uses of GenAI are not permitted:
                                <ul>
                                    <li>Generating original research data, results, interpretations, or conclusions</li>
                                    <li>Fabricating, falsifying, or manipulating data or images</li>
                                    <li>Creating or altering figures, tables, or images in a misleading manner</li>
                                    <li>Producing entire manuscripts or substantial sections without meaningful human authorship</li>
                                    <li>
                                        Listing GenAI tools or systems as authors or coauthors
                                    </li>
                                </ul>
                            </div>



                            <div>
                                <b>Disclosure of GenAI Use</b>
                                To ensure transparency, authors must disclose the use of GenAI or AIassisted technologies in manuscript preparation. Disclosure should be provided in a dedicated section titled “Declaration of Generative AI and AIAssisted Technologies in Writing” and must include:
                                <ul>
                                    <li>The name of the AI tool used</li>
                                    <li>The specific purpose for which it was used</li>
                                </ul>
                            </div>
                            <div>
                                Example:
                            </div>
                            <div>
                                During the preparation of this manuscript, the authors used [tool name] to assist with language editing and readability. All content was reviewed and approved by the authors, who take full responsibility for the final version.
                            </div>
                            <div>
                                Failure to disclose GenAI use may be considered a breach of publication ethics.

                            </div>


                            <div>
                                <b>Use of GenAI in Peer Review and Editorial Processes</b>
                            </div>
                            <div>
                                <ul>
                                    <li>Reviewers and editors must not upload manuscripts, reviewer reports, or confidential information into external GenAI systems</li>
                                    <li>
                                        GenAI tools must not be used to generate peer review reports or editorial decisions
                                    </li>
                                    <li>
                                        Editorial judgment and peer review must be conducted solely by qualified human experts
                                    </li>
                                </ul>
                            </div>


                            <div>
                                <b>Plagiarism, Originality, and Detection</b>
                                <ul>
                                    <li>All submissions may be screened using plagiarism detection and AIcontent detection tools</li>
                                    <li>Manuscripts containing excessive AIgenerated content or undisclosed AI use may be rejected or returned for revision
                                    </li>
                                    <li>
                                        ISAR Publisher follows COPE guidelines when investigating suspected misuse of GenAI
                                    </li>
                                </ul>
                            </div>



                            <div>
                                <b>Responsibility and Accountability</b>
                            </div>
                            <div>
                                The use of GenAI does not diminish or transfer responsibility. Authors, editors, and reviewers remain fully accountable for all content, decisions, and ethical compliance associated with published works.

                            </div>

                            <div>
                                <b>Compliance and Policy Updates</b>
                            </div>
                            <div>
                                Noncompliance with this policy may result in manuscript rejection, correction, retraction, or other editorial actions. ISAR Publisher reserves the right to revise this policy periodically to reflect technological developments, ethical standards, and indexing requirements.
                            </div>

                            <div>
                                <b>ISAR Publisher</b>
                            </div>
                            <div>
                                Website: <a href="https://isarpublisher.com">https://isarpublisher.com </a> <br />
                                Email: <a href="mailto:contact@isarpublisher.com">contact@isarpublisher.com</a> 
                            </div>
                        </p> */}



                    </div>
                </div>
            </Card>
        </>
    );
};

export default AboutUs;
