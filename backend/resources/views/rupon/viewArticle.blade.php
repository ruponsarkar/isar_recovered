<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{$article->name}}</title>

        <meta
            name="description"
            content="{!! Str::limit($article->abstract, 160, '.') !!}"
        />

        <meta name="author" content="{{$article->aname}}" />
        <meta name="keywords" content="{{$article->keywords}}">

        <meta
            name="DC.Description"
            xml:lang="en"
            content="{!! Str::limit($article->abstract, 160, '.') !!}"
        />
        <meta name="DC.Identifier.DOI" content="{{$article->doi}}" />
        <meta name="DC.Identifier.URI" content="{{url()->current()}}" />
        <meta name="DC.Language" content="en" />
        <meta name="DC.Source" content="{{$article->j_name}}" />
        <meta name="DC.Source.ISSN" content="{{$article->issn}}" />
        <meta name="citation_author" content="{{$article->aname}}" />

        <meta name="DC.Date.created" content="{{ $article->published_date }}" />

        <meta property="og:type" content="Publishers">
        <meta property="og:title" content="{{$article->name}}">
        <meta property="og:description" content="{!! Str::limit($article->abstract, 160, '.') !!}">
        <meta property="og:image" content="https://isarpublisher.com/backend/public/assets/journal/{{$article->photo}}">

        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
        />
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <div class="bg-secondary p-1 text-white text-end">
            <div class="mx-3">
                <a
                    href="https://wa.me/9085848287?text=Hii"
                    style="color: white"
                >
                    <i class="fa fa-whatsapp" style="font-size: 1.5rem;"></i>
                </a>
                &nbsp;
                <a
                    href="https://www.facebook.com/profile.php?id=61552592232822&mibextid=ZbWKwL"
                    target="_blank"
                    style="color: white"
                >
                    <i class="fa fa-facebook" style="font-size: 1.5rem;"></i>
                    
                </a>
                &nbsp;
                <a href="#" style="color: white">
                    <i class="fa fa-instagram" style="font-size: 1.5rem;"></i>
                </a>
            </div>
        </div>

        <div
            class="p-1"
            style="
                background-image: url('https://isarpublisher.com/assets/logobg.jpg');
                background-size: cover;
                background-position: center;
            "
        >
        <div classname="mx-1 d-flex align-items-center" style="
    display: flex;
    align-items: center;
">
                <img src="https://isarpublisher.com/assets/mainlogo.png" width="150" classname="logo-img" alt="ISAR">
                <h3 class="px-4 text-white" style="text-shadow: 2px 2px rgb(21 78 179)">
                    <strong>
                        International Scientific and Academic Research Publisher
                    </strong>
                </h3>
            </div>
           
        </div>

        <nav
            class="navbar navbar-expand-lg navbar-light botton-line"
            style="background-color: #8568fb"
        >
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="#"> ISAR Publisher </a>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div
                    class="offcanvas offcanvas-end"
                    tabindex="-1"
                    id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel"
                >
                    <div class="offcanvas-header">
                        <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul
                            class="navbar-nav justify-content-end flex-grow-1 pe-3"
                        >
                            <li class="nav-item">
                                <a class="nav-link scrollt text-white" href="https://isarpublisher.com/"
                                    >Home</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link scrollt text-white"
                                    href="https://isarpublisher.com/AllJournals"
                                    >Journals</a
                                >
                            </li>

                            <li class="nav-item dropdown">
                                <a
                                    class="nav-link dropdown-toggle text-white"
                                    href="#"
                                    id="forAuthorsDropdown"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    For Authors
                                </a>
                                <ul
                                    class="dropdown-menu"
                                    aria-labelledby="forAuthorsDropdown"
                                >
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="https://isarpublisher.com/author-instruction"
                                            >Authors Guidelines</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="https://isarpublisher.com/joinAuthor"
                                            >Join Author</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="https://isarpublisher.com/open-access-policy"
                                            >Open Access Policy</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="https://isarpublisher.com/reviewProcess"
                                            >Review Process</a
                                        >
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a
                                    class="nav-link dropdown-toggle text-white"
                                    href="#"
                                    id="forEditorsDropdown"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    For Editors
                                </a>
                                <ul
                                    class="dropdown-menu"
                                    aria-labelledby="forAuthorsDropdown"
                                >
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="https://isarpublisher.com/EditorGuidlines"
                                            >Editors Guidelines</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="https://isarpublisher.com/joinEditor"
                                            >Join Editor</a
                                        >
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a
                                    class="nav-link scrollt text-white"
                                    href="https://isarpublisher.com/aboutUs"
                                    >About Us</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link scrollt text-white"
                                    href="https://isarpublisher.com/PublicationFee"
                                    >Publication Fee</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link scrollt text-white"
                                    href="https://isarpublisher.com/Payment"
                                    >Payment</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link scrollt text-white"
                                    href="action"
                                    >Contact Us</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row mt-2">
                <div class="col-md-8 border p-2">
                    <div class="text-center">
                        <h4>
                            {{$article->name}}
                        </h4>
                    </div>

                    <hr />

                    <div class="pb-2">
                        <span>Author: </span>
                        <strong>{{$article->aname}}</strong>
                    </div>
                    <div class="pb-2">
                        <span>Published Date: </span>
                        <strong>{{$article->published_date}}</strong>
                    </div>
                    <div class="pb-2">
                        <span>GoogleScholar: </span>
                        <strong
                            ><a href="{{$article->googleScholar}}"
                                >Click here
                            </a>
                        </strong>
                    </div>
                    
                     <div class="pb-2">
                        <span>Keywords: </span>
                        <i
                            >{{$article->keywords}}
                        </i>
                    </div>
                    
                    
                    
                    
                    <div class="pb-2">
                        <strong>Abstract: </strong>
                        <div>
                            {!! $article->abstract !!}
                        </div>
                    </div>

                    <!-- <div class="text-center py-3 fixed-bottom">
                        <button class="btn btn-success">
                            <i class="fa fa-download"></i> &nbsp; Download
                        </button>
                    </div> -->
                </div>
                <div class="col-md-4 border">
                    <div class="row">
                        <div class="text-center py-3">
                            <a href="/download/{{$article->airticle_id}}" class="btn btn-success">
                                <i class="fa fa-download"></i> &nbsp; Download
                            </a>
                            <button class="btn btn-secondary">
                                <i class="fa fa-eye"></i> &nbsp; {{$article->count}}
                            </button>
                        </div>

                        <hr />

                        

                        <div class="m-2">
                            <div>
                                Journal: <strong> {{$article->j_name}} </strong>
                            </div>
                            <div>
                                ISSN(Online): <i> {{$article->issn}} </i>
                            </div>
                            <div>
                                Publisher: <strong> ISAR Publishers </strong>
                            </div>
                            <div>
                                Frequency:
                                <strong> {{$article->frequency}} </strong>
                            </div>
                            <div>
                                <!-- Chief Editor: <strong>  {{$article->chief_editor}}  </strong> -->
                            </div>
                            <div>
                                Language:
                                <strong> {{$article->language}} </strong>
                            </div>
                        </div>

                        <hr />

                        <div class="text-center m-2">
                            <img
                                src="https://isarpublisher.com/backend/public/assets/journal/{{$article->photo}}"
                                class="col-md-7"
                                alt="{{$article->name}}"
                                style="width: 80%;"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-dark">
            <div
                class="d-flex align-items-center justify-content-between text-white"
            >
                <div class="m-4">Get connected with us on social networks:</div>

                 <div class="mx-2 px-2"><i class="fa fa-facebook"></i> &nbsp; <i class="fa fa-instagram"></i>  &nbsp;  </div>
            </div>
            <hr class="text-white opacity-100" />

            <div class="row text-white p-0 m-0">
                <div class="col-md-4 p-4">
                    <div class="d-flex justify-content-center">
                        <div class="">
                            <div class="mb-3">
                                <strong> USEFUL LINKS </strong>
                            </div>

                            <div class="mb-2">
                                <a
                                    class="text-white text-decoration-none"
                                    href="https://isarpublisher.com/AllJournals"
                                >
                                    Journal
                                </a>
                            </div>
                            <div class="mb-2">
                                <a
                                    class="text-white text-decoration-none"
                                    href="https://isarpublisher.com/refundpolicy"
                                >
                                    CANCELLATION/REFUND POLICY
                                </a>
                            </div>
                            <div class="mb-2">
                                <a
                                    class="text-white text-decoration-none"
                                    href="https://isarpublisher.com/author-instruction"
                                >
                                    Authors Guidlines
                                </a>
                            </div>
                            <div class="mb-2">
                                <a
                                    class="text-white text-decoration-none"
                                    href=""
                                >
                                    Contact
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-4">
                    <div class="d-flex justify-content-center">
                        <div class="">
                            <div class="mb-3">
                                <strong> OPEN ACCESS LICENSE </strong>
                            </div>

                            <div class="mb-2">
                                <img
                                    src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png"
                                    width="100"
                                    alt=""
                                />
                                <a class="text-white" href="https://creativecommons.org/licenses/by-nc/4.0/">
                                    This work is licensed under a Creative
                                    Commons Attribution-NonCommercial 4.0
                                    International License
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-4">
                    <div class="d-flex justify-content-center">
                        <div class="">
                            <div class="mb-3">
                                <strong> Contact </strong>
                            </div>

                            <div class="mb-2">
                                <i class="fa fa-home">
                                    Ghaniarpar, Ambari, Hojai, Assam, 782445
                                    (India)
                                </i>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-envelope">
                                    contact@isarpublisher.com
                                </i>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-phone"> +91 9085848287 </i>
                            </div>
                            <div class="mb-2">
                                <i class="fa fa-phone"> +91 8474849463 </i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center m-2 pb-2 text-white">
                © Copyright 2024 ISAR Publisher. All Rights Reserved
            </div>
            <div class="text-center m-2 pb-2 text-white">
                Developed by : <a href="https://pageuptechnologies.com/"> Pageuptechnologies.com </a>
            </div>
        </div>
    </body>
</html>
