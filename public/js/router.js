
function loadScripts(element, appendTo) {
    
    if (element) {
      appendTo.innerHTML = '<span>Working</span>';

      window.addEventListener("DOMContentLoaded", function(e) {
      
        const scriptElements = element.querySelectorAll("script");

        function loadScript(script) {
            return new Promise((resolve, reject) => {
                const newScript = document.createElement("script");
                newScript.src = script.src;
                newScript.type = 'module';
                newScript.addEventListener("load", resolve);
                newScript.addEventListener("error", reject);
                appendTo.appendChild(newScript);
            });
        }

        // Load all scripts sequentially
        scriptElements.forEach((script) => {
            if (script.src) {
                loadScript(script)
                    .then(() =>
                        console.log(
                            `Script "${script.src}" loaded successfully.`
                        )
                    )
                    .catch((error) =>
                        console.error(
                            `Error loading script "${script.src}":`,
                            error
                        )
                    );
            } else {

              const newScript = document.createElement("script");
              newScript.type = 'module';
              newScript.textContent = script.textContent;
              appendTo.appendChild(newScript);
               
                
            }
        });
      });
    }
}

function isLocal(route) {
    return (
        (/https?:\/\//.test(route) &&
            route.startsWith(window.location.origin)) ||
        !/https?:\/\//.test(route)
    );
}

function updatePageContent(route) {
    if (isLocal(route)) {
        fetch(route)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.text();
            })
            .then((text) => {
                var parser = new DOMParser();
                var htmlDoc = parser.parseFromString(text, "text/html");
                const destination = document.querySelector("#main-slot");
                const source = htmlDoc.querySelector("#main-slot");
                if (destination && source) {
                    window.history.pushState(null, "", route);
                    destination.innerHTML = source.innerHTML;

                    const attrs = Array.from(
                        htmlDoc.querySelector("html").attributes
                    );

                    attrs.forEach((attr) => {
                        const value = attr.nodeValue;
                        const name = attr.name;
                        destination
                            .querySelector("html")
                            ?.setAttribute(name, value);
                    });
                    const footer = htmlDoc.querySelector("#footer-slot");
                    const destinationFooter =
                        document.querySelector("#footer-slot");

                    if (destinationFooter && footer) {

                            loadScripts(footer, destinationFooter);

                        // htmlDoc
                        //     .querySelectorAll("#main-slot script")
                        //     .forEach((element) => {
                        //         const src = element.getAttribute("src");

                        //         const script = document.createElement("script");
                        //         script.type = "module";

                        //         if (src) {
                        //             script.src = element.getAttribute("src");
                        //         } else {
                        //             script.textContent = element.textContent;
                        //         }

                        //         destinationFooter.appendChild(script);
                        //     });

                        // htmlDoc
                        //     .querySelectorAll("#footer-slot script[src]")
                        //     .forEach((element) => {
                        //         const script = document.createElement("script");
                        //         script.type = "module";
                        //         script.src = element.getAttribute("src");
                        //         console.log({ src: script.src });

                        //         $.getScript(script.src, function (sc) {
                        //             //console.log(sc);
                        //             destinationFooter.appendChild(script);
                        //         });
                        //     });
                    }
                } else {
                    throw new Error("Failed to fetch page");
                }
            })
            .catch((err) => {
                console.log(err);
                const html = document.createElement("div");
                const img = document.createElement("img");
                const wrapper = document.createElement("div");
                const actions = document.createElement("span");
                const reloadLink = document.createElement("a");
                const backLink = document.createElement("a");
                const message = document.createElement("div");

                html.setAttribute("id", "error-overlay");
                img.classList.add("justify-self-center");
                img.src = "http://127.0.0.1:8000/images/no-course.png";
                actions.classList.add("actions");
                reloadLink.setAttribute("href", window.location.href);
                backLink.setAttribute("href", "/");
                message.innerText = err.message;
                message.classList.add("error-message");
                wrapper.classList.add("error-wrapper");

                reloadLink.innerText = "Reload";
                backLink.innerText = "Dashboard";

                actions.appendChild(reloadLink);
                actions.appendChild(backLink);

                wrapper.appendChild(img);
                wrapper.appendChild(message);
                wrapper.appendChild(actions);

                html.appendChild(wrapper);

                const errorOverlay = document.querySelector("#error-overlay");
                if (errorOverlay) {
                    errorOverlay.remove();
                }

                document.querySelector("body").appendChild(html);
            });
    } else {
        window.location.href = route;
    }
}

$(document).on("click", 'aa[href]:not([href="/logout"])', function (e) {
    e.preventDefault();
    const route = $(this).attr("href");

    updatePageContent(route);
});
