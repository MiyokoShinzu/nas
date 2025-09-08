<?php include './globals/head.php'; ?>

<body class="bg-white">
    <?php include './globals/nav.php'; ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .sl {
            transition: all 0.3s ease;
        }

        .sl:hover {
            scale: 1.1;
            box-shadow: 1px 1px 10px var(--bs-primary);
            border-radius: 50%;
        }
    </style>
    <!-- 🔹 First row with canvas background -->
    <div class="container-fluid d-flex align-items-center justify-content-center flex-column">
        <div class="row" style="height: 100vh;background: #fff;  overflow: none;">
            <!-- Canvas only inside this row -->
            <canvas id="network-bg" class="w-100"></canvas>

            <div data-aos="fade-down" class="col-11 col-lg-10 mx-auto d-flex align-items-center justify-content-center flex-column position-relative" style="z-index: 1;">
                <div class="wrapper d-flex align-items-center justify-content-around flex-row">
                    <img class="mb-2 mx-3" src="./assets/csu.png" alt="" style="height: 50px; width: 50px; ">
                    <img class="mb-2 mx-3" src="./assets/coea.png" alt="" style="height: 50px; width: 50px;">
                    <img class="mb-2 mx-3" src="./assets/icpep.png" alt="" style="height: 50px; width: 50px; ">
                </div>
                <div class="masking-container text-center">
                    <h1 class="masked-text">NɅS Club Members</h1>
                    <style>
                        .masked-text {
                            font-size: 4em;
                            font-weight: bold;
                            color: transparent;
                            background-image: url('https://static.vecteezy.com/system/resources/thumbnails/017/446/303/small/technology-abstract-lines-and-dots-connection-background-connection-digital-data-and-big-data-concept-network-connection-dots-and-lines-futuristic-background-illustration-vector.jpg');
                            background-size: 200%;
                            background-position: 0 50%;
                            background-clip: text;
                            -webkit-background-clip: text;
                            -webkit-text-fill-color: transparent;
                            animation: animate-background 5s infinite alternate linear;
                        }

                        nav.navbar {
                            position: relative;
                            z-index: 10;
                            /* above the canvas */
                        }

                        @keyframes animate-background {
                            0% {
                                background-position: 0 50%;
                            }

                            100% {
                                background-position: 100% 50%;
                            }
                        }
                    </style>
                </div>
                <p class="text-center text-muted">
                    Introducing the NAS Club's diverse group of students, officers, and mentors united to learn, share, and grow together in technology and innovation.
                </p>
            </div>
        </div>





        <div class="row mt-3  mb-3">
            <div class="col-lg-10  mx-auto text-center col-11 mb-5">
                <span
                    class="alert alert-primary fw-bold ">Officers of the NAS Club</span>

            </div>
            <div class="col-11 col-lg-4 mx-auto d-flex align-items-center justify-content-center mb-5" data-aos="fade-right">
                <div class="container1 ">
                    <div class="card_box d-flex align-items-center justify-content-center flex-column border shadow">
                        <img src="./assets/adviser.jpg" class="mb-3" style="height: 120px; width: 120px; border-radius: 50%;" alt="">
                        <p class="text-dark text-center fw-bold border-bottom border-2 border-primary" style="">Engr. Karl Stephen O. Evallo</p>
                        <div class="social-links d-flex align-items-center justify-content-center gap-3 mb-3">
                            <a href="https://web.facebook.com/evkarlstephen" class="sl">
                                <img src="https://www.svgrepo.com/show/452196/facebook-1.svg" style="height: 30px; width: 30px;" alt="">
                            </a>
                            <a href="https://www.linkedin.com/in/evallokarlstephen/" class="sl">
                                <img src="https://www.svgrepo.com/show/452047/linkedin-1.svg" style="height: 30px; width: 30px;" alt="">
                            </a>
                            <a href="https://ets-dev.com" class="sl">
                                <img src="https://www.svgrepo.com/show/231180/mail.svg" style="height: 30px; width: 30px;" alt="">
                            </a>
                        </div>
                        <span></span>
                    </div>
                </div>

                <style>
                    /* From Uiverse.io by mrhyddenn */
                    .container1 {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .card_box {
                        width: 300px;
                        height: 250px;
                        background: var(--bs-white);
                        position: relative;
                        /* box-shadow: 0 25px 50px rgba(3, 104, 141, 0.55); */
                        cursor: pointer;
                        transition: all .3s;
                    }

                    .card_box:hover {
                        transform: scale(0.9);
                    }

                    .card_box span {
                        position: absolute;
                        overflow: hidden;
                        width: 150px;
                        height: 150px;
                        top: -10px;
                        left: -10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .card_box span::before {
                        content: 'Adviser';
                        position: absolute;
                        width: 150%;
                        height: 40px;
                        background-image: linear-gradient(45deg, #1d0ac8ff 0%, #ee07faff 51%, #e811b2ff 100%);
                        transform: rotate(-45deg) translateY(-20px);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #fff;
                        font-weight: 600;
                        letter-spacing: 0.1em;
                        text-transform: uppercase;
                        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.23);
                    }

                    .card_box span::after {
                        content: '';
                        position: absolute;
                        width: 10px;
                        bottom: 0;
                        left: 0;
                        height: 10px;
                        z-index: -1;
                        box-shadow: 140px -140px #790586ff;
                        background-image: linear-gradient(45deg, #114ae6ff 0%, #4019f0ff 51%, #ff4e2fff 100%);
                    }
                </style>
            </div>


            <div class="col-11 col-lg-4 mx-auto d-flex align-items-center justify-content-center mb-5" data-aos="fade-down">
                <div class="container2 ">
                    <div class="card_box2 d-flex align-items-center justify-content-center flex-column border shadow">
                        <img src="./assets/president.jpg" class="mb-3" style="height: 120px; width: 120px; border-radius: 50%;" alt="">
                        <p class="text-dark text-center fw-bold border-bottom border-2 border-primary" style="">Mr. Harvey Beldua</p>
                        <div class="social-links d-flex align-items-center justify-content-center gap-3 mb-3">
                            <a href="https://web.facebook.com/harvey.beldua" class="sl">
                                <img src="https://www.svgrepo.com/show/452196/facebook-1.svg" style="height: 30px; width: 30px;" alt="">
                            </a>

                            <a href="https://belduaharvey.great-site.net/?i=1" class="sl">
                                <img src="https://www.svgrepo.com/show/231180/mail.svg" style="height: 30px; width: 30px;" alt="">
                            </a>
                        </div>
                        <span></span>
                    </div>
                </div>

                <style>
                    /* From Uiverse.io by mrhyddenn */
                    .container2 {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .card_box2 {
                        width: 300px;
                        height: 250px;
                        background: var(--bs-white);
                        position: relative;
                        /* box-shadow: 0 25px 50px rgba(3, 104, 141, 0.55); */
                        cursor: pointer;
                        transition: all .3s;
                    }

                    .card_box2:hover {
                        transform: scale(0.9);
                    }

                    .card_box2 span {
                        position: absolute;
                        overflow: hidden;
                        width: 150px;
                        height: 150px;
                        top: -10px;
                        left: -10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .card_box2 span::before {
                        content: 'President';
                        position: absolute;
                        width: 150%;
                        height: 40px;
                        background-image: linear-gradient(45deg, #1d0ac8ff 0%, #ee07faff 51%, #e811b2ff 100%);
                        transform: rotate(-45deg) translateY(-20px);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #fff;
                        font-weight: 600;
                        letter-spacing: 0.1em;
                        text-transform: uppercase;
                        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.23);
                    }

                    .card_box2 span::after {
                        content: '';
                        position: absolute;
                        width: 10px;
                        bottom: 0;
                        left: 0;
                        height: 10px;
                        z-index: -1;
                        box-shadow: 140px -140px #790586ff;
                        background-image: linear-gradient(45deg, #114ae6ff 0%, #4019f0ff 51%, #ff4e2fff 100%);
                    }
                </style>
            </div>

            <div class="col-11 col-lg-4 mx-auto d-flex align-items-center justify-content-center mb-5" data-aos="fade-left">
                <div class="container3 ">
                    <div class="card_box3 d-flex align-items-center justify-content-center flex-column border shadow">
                        <img src="./assets/vicepresident.jpg" class="mb-3" style="height: 120px; width: 120px; border-radius: 50%;" alt="">
                        <p class="text-dark text-center fw-bold border-bottom border-2 border-primary" style="">Ms. Precious Cauilan</p>
                        <div class="social-links d-flex align-items-center justify-content-center gap-3 mb-3">
                            <a href="https://web.facebook.com/its.sachiee" class="sl">
                                <img src="https://www.svgrepo.com/show/452196/facebook-1.svg" style="height: 30px; width: 30px;" alt="">
                            </a>

                            <a href="https://preciouscauilan.great-site.net/?i=1" class="sl">
                                <img src="https://www.svgrepo.com/show/231180/mail.svg" style="height: 30px; width: 30px;" alt="">
                            </a>
                        </div>
                        <span></span>
                    </div>
                </div>

                <style>
                    /* From Uiverse.io by mrhyddenn */
                    .container3 {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .card_box3 {
                        width: 300px;
                        height: 250px;
                        background: var(--bs-white);
                        position: relative;
                        /* box-shadow: 0 25px 50px rgba(3, 104, 141, 0.55); */
                        cursor: pointer;
                        transition: all .3s;
                    }

                    .card_box3:hover {
                        transform: scale(0.9);
                    }

                    .card_box3 span {
                        position: absolute;
                        overflow: hidden;
                        width: 150px;
                        height: 150px;
                        top: -10px;
                        left: -10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .card_box3 span::before {
                        content: 'Secretary';
                        position: absolute;
                        width: 150%;
                        height: 40px;
                        background-image: linear-gradient(45deg, #1d0ac8ff 0%, #ee07faff 51%, #e811b2ff 100%);
                        transform: rotate(-45deg) translateY(-20px);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #fff;
                        font-weight: 600;
                        letter-spacing: 0.1em;
                        text-transform: uppercase;
                        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.23);
                    }

                    .card_box3 span::after {
                        content: '';
                        position: absolute;
                        width: 10px;
                        bottom: 0;
                        left: 0;
                        height: 10px;
                        z-index: -1;
                        box-shadow: 140px -140px #790586ff;
                        background-image: linear-gradient(45deg, #114ae6ff 0%, #4019f0ff 51%, #ff4e2fff 100%);
                    }
                </style>
            </div>


        </div>
        <div class="row mb-3 mt-3   shadow bg-white p-3">
            <div class="col-lg-10 col-11 mx-auto mt-3 mb-3">
                <h5 class="tentative text-center fw-bold border-bottom border-primary border-2 pb-2">Tentative Members of the Club</h5>

            </div>
            <div class="col-lg-10 mb-4 text-center mx-auto  mt-1">
                <span class=" mb-2 mt-2 text-danger text-center">
                    Tentative members are required to take an examination before initial orientation. The exam schedule and procedures will be posted later.

                    <span
                        class="badge rounded-pill text-bg-danger text-white">Keep in touch and updated through our website!</span>

                </span>
            </div>

            <div class="col-lg-10 mx-auto col-11">
                <div class="table-responsive">
                    <table class="table table-hover" id="members_table">
                        <thead>
                            <tr>
                                <th class="text-center">Full Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Yr. Section</th>
                                <th class="text-center">Datetime Pre-registered</th>
                                <th class="text-center">S/C</th>
                            </tr>
                        </thead>
                        <tbody id="members_table_data">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    <!-- 🔹 Canvas Styles -->
    <style>
        html,
        body {
            overflow-x: hidden;
        }

        #network-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            /* replaces inset: 0 */
            bottom: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }
    </style>

    <script>
        document.querySelectorAll(".network-card-bg").forEach(canvas => {
            const ctx = canvas.getContext("2d");
            let width = 0,
                height = 0,
                DPR = window.devicePixelRatio || 1;

            function resize() {
                const parent = canvas.parentElement;
                width = parent.clientWidth;
                height = parent.clientHeight;
                canvas.width = width * DPR;
                canvas.height = height * DPR;
                canvas.style.width = width + "px";
                canvas.style.height = height + "px";
                ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
            }
            resize();
            window.addEventListener("resize", resize);

            const nodes = [];
            const nodeCount = 25;

            function rand(min, max) {
                return Math.random() * (max - min) + min
            }

            function randColor() {
                const hues = [220, 270]; // blue to violet
                return `hsl(${rand(hues[0],hues[1])}, 80%, 60%)`;
            }

            for (let i = 0; i < nodeCount; i++) {
                nodes.push({
                    x: rand(0, width),
                    y: rand(0, height),
                    vx: rand(-0.6, 0.6),
                    vy: rand(-0.6, 0.6),
                    r: rand(2, 5),
                    color: randColor()
                });
            }

            function draw() {
                ctx.clearRect(0, 0, width, height);

                // lines
                for (let i = 0; i < nodes.length; i++) {
                    for (let j = i + 1; j < nodes.length; j++) {
                        const a = nodes[i],
                            b = nodes[j];
                        const dx = a.x - b.x,
                            dy = a.y - b.y;
                        const d = Math.hypot(dx, dy);
                        if (d < 120) {
                            ctx.beginPath();
                            ctx.moveTo(a.x, a.y);
                            ctx.lineTo(b.x, b.y);
                            ctx.strokeStyle = `rgba(111,66,193,${1-d/120})`;
                            ctx.stroke();
                        }
                    }
                }

                // nodes
                for (const n of nodes) {
                    ctx.beginPath();
                    ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
                    ctx.fillStyle = n.color;
                    ctx.fill();
                }
            }

            function step() {
                for (const n of nodes) {
                    n.x += n.vx;
                    n.y += n.vy;
                    if (n.x < 0 || n.x > width) n.vx *= -1;
                    if (n.y < 0 || n.y > height) n.vy *= -1;
                }
                draw();
                requestAnimationFrame(step);
            }
            step();
        });
    </script>

    <script>
        (function() {
            const canvas = document.getElementById('network-bg');
            const ctx = canvas.getContext('2d');

            let width = 0,
                height = 0,
                DPR = Math.max(1, window.devicePixelRatio || 1);

            function resize() {
                const parent = canvas.parentElement;
                width = parent.clientWidth;
                height = parent.clientHeight;
                canvas.width = Math.floor(width * DPR);
                canvas.height = Math.floor(height * DPR);
                canvas.style.width = width + 'px';
                canvas.style.height = height + 'px';
                ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
            }
            window.addEventListener('resize', resize, {
                passive: true
            });
            resize();

            const config = {
                nodeCount: 15,
                speed: 0.8,
                maxLinkDistance: 100
            };

            let nodes = [];

            function rand(min, max) {
                return Math.random() * (max - min) + min;
            }

            function createNodes() {
                nodes = [];
                for (let i = 0; i < config.nodeCount; i++) {
                    nodes.push({
                        x: rand(0, width),
                        y: rand(0, height),
                        vx: rand(-config.speed, config.speed),
                        vy: rand(-config.speed, config.speed),
                        r: rand(2, 7),
                        hue: rand(200, 280)
                    });
                }
            }

            function draw() {
                ctx.clearRect(0, 0, width, height);
                for (let i = 0; i < nodes.length; i++) {
                    const a = nodes[i];
                    for (let j = i + 1; j < nodes.length; j++) {
                        const b = nodes[j];
                        const dx = a.x - b.x,
                            dy = a.y - b.y,
                            d = Math.hypot(dx, dy);
                        if (d < config.maxLinkDistance) {
                            const alpha = 1 - (d / config.maxLinkDistance);
                            ctx.beginPath();
                            ctx.moveTo(a.x, a.y);
                            ctx.lineTo(b.x, b.y);
                            ctx.strokeStyle = `hsla(250, 70%, 65%, ${alpha * 0.4})`;
                            ctx.lineWidth = 1;
                            ctx.stroke();
                        }
                    }
                }

                // draw nodes
                for (const n of nodes) {
                    ctx.beginPath();
                    ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
                    ctx.fillStyle = `hsl(${n.hue}, 80%, 60%)`;
                    ctx.fill();
                }
            }

            function step() {
                for (const n of nodes) {
                    n.x += n.vx;
                    n.y += n.vy;
                    if (n.x < 0 || n.x > width) n.vx *= -1;
                    if (n.y < 0 || n.y > height) n.vy *= -1;
                }
                draw();
                requestAnimationFrame(step);
            }

            createNodes();
            step();
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.1.8/af-2.7.0/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/date-1.5.4/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.3/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.1/sp-2.3.3/sl-2.1.0/sr-1.4.1/datatables.min.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
        fetch(`./api/select_members.php`)
            .then(res => res.json())
            .then(data => {
                var text = ""
                var s = ""
                data.forEach(item => {
                    text += `<tr>`
                    if (item.status == 1) {
                        s = ' <span class = "badge rounded-pill text-white text-bg-success" > Approved </span > '
                    } else {
                        s = ' <span class = "badge rounded-pill text-white text-bg-warning" > Pending </span > '
                    }
                    text += `<td class="text-center">${item.firstname} ${item.middlename} ${item.lastname} ${item.suffix}</td>`
                    text += `<td class="text-center">${s}</td>`
                    text += `<td class="text-center">${item.email}</td>`
                    text += `<td class="text-center">CPE-${item.year}${item.section}</td>`
                    text += `<td class="text-center">${item.datetime_registered}</td>`
                    text += `<td class="text-center">${item.suggestion}</td>`



                    text += `</tr>`
                })
                $('#members_table_data').html(text)
                $('#members_table').DataTable({
                    dom: 'frBtip',
                    responsive: true,
                    buttons: [


                        {
                            extend: 'colvis',
                            text: 'Show/Hide Columns'
                        }
                    ],
                    fixedHeader: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    scrollY: '300px',
                    colReorder: true,
                    scrollCollapse: true,
                    language: {
                        search: 'Search:'
                    }
                });
            })
            .catch(err => console.error(err))
    </script>

</body>

</html>