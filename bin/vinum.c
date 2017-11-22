#include <stdio.h>
#include <string.h>
#include <stdlib.h>



// Define the colors for the terminal
#define KNORMAL   "\x1B[0m"
#define KRED      "\x1B[31m"
#define KGREEN    "\x1B[32m"
#define KYELLOW   "\x1B[33m"
#define KBLUE     "\x1B[34m"
#define KMAGENTA  "\x1B[35m"
#define KCYAN     "\x1B[36m"
#define KWHITE    "\x1B[37m"

// Define the location of the vinum executable
#define EXECLOC "config/routes.json"

// Other Constants
#define BUFFER_SIZE 5000
#define MAX_TOKEN_COUNT 128
#define VERSION "0.0.1"

// Function prototypes
void print_vinum(void);
void display_help_menu(void);
void display_routes(void);
void display_version(void);
void display_not_available(void);
void not_found(void);
void generations(int, char*[]);
void generate_controller(char*);
void generate_model(char*);
void generate_resource(char*);
void generate_migration(char*);
void generate_view(char*, char*);



int main(int argc, char *argv[]) {

  // If the number of arguments is only 1, then print a message and exit
  if (argc < 2) {
    print_vinum();
    printf("%sSorry you need to specify what you want to do. Do '", KNORMAL);
    printf("%svinum --help", KYELLOW);
    printf("%s' to get help.\n\n", KNORMAL);
    return 0;
  }

  // if they are asking for help
  if (argc > 1 && strcmp(argv[1], "--help") == 0) {
    display_help_menu();
    return 0;
  }

  // If the users want to see al the routes
  if (argc > 1 && (strcmp(argv[1], "routes") == 0 || strcmp(argv[1], "r") == 0)) {
    display_routes();
    return 0;
  }

  // If the user wants to see the version
  if (argc > 1 && (strcmp(argv[1], "--version") == 0 || strcmp(argv[1], "--v") == 0)) {
    display_version();
    return 0;
  }


  // If they want to migrate the Database
  if (argc > 1 && (strcmp(argv[1], "db:migrate") == 0)) {
    display_not_available();
    return 0;
  }



  // If they are trying to generate something
  if (argc > 2 && (strcmp(argv[1], "generate") == 0 || strcmp(argv[1], "g") == 0)) {
    generations(argc, argv);

    return 0;
  }


  // If the user wants to create a new application
  if (argc == 3 && strcmp(argv[1], "new") == 0) {
    display_not_available();

    return 0;
  }


  // If they try to to look for something not shown on the list above, then show a little message, then show the list of options and quit
  not_found();
  return 0;
}




// This function will print the menu of 'Vinum:'
void print_vinum() {
  printf("\n%sVinum: %s", KCYAN, KNORMAL);
}


// This function will print the help menu
void display_help_menu() {
  printf("\n%s*******************************\n*****       ACTIONS       *****\n*******************************%s\n", KBLUE, KNORMAL);
  printf("\n%sStart a new APP:\n", KNORMAL);
  printf("\t'%svinum new [APP_PATH]%s'\n", KYELLOW, KNORMAL);
  printf("\n%sGenerate a new Controller:\n", KNORMAL);
  printf("\t'%svinum generate controller [CONTROLLER_NAME]%s'\n", KYELLOW, KNORMAL);
  printf("\n%sGenerate a new Model:\n", KNORMAL);
  printf("\t'%svinum generate model [MODEL_NAME]%s'\n", KYELLOW, KNORMAL);
  printf("\n%sGenerate a new View:\n", KNORMAL);
  printf("\t'%svinum generate view [VIEW_NAME]%s'\n", KYELLOW, KNORMAL);
  printf("\n%sGenerate a new Resource:\n", KNORMAL);
  printf("\t'%svinum generate resource [RESOURCE_NAME]%s'\n", KYELLOW, KNORMAL);
  printf("\n%sGenerate a new Migration:\n", KNORMAL);
  printf("\t'%svinum generate migration [MIGRATION_NAME]%s'\n", KYELLOW, KNORMAL);
  printf("\n%sMigrate the Database:\n", KNORMAL);
  printf("\t'%svinum db:migrate%s'\n", KYELLOW, KNORMAL);
  printf("\n%s*******************************\n*****       OPTIONS       *****\n*******************************%s\n", KBLUE, KNORMAL);
  printf("\n%sShow Help Menu:\n", KNORMAL);
  printf("\t'%svinum --help%s'\n", KYELLOW, KNORMAL);
  printf("\n%sShow Version Number:\n", KNORMAL);
  printf("\t'%svinum --version%s'\n", KYELLOW, KNORMAL);
  printf("\n%s*******************************\n***** DISPLAY INFORMATION *****\n*******************************%s\n", KBLUE, KNORMAL);
  printf("\n%sDisplay all Routes:\n", KNORMAL);
  printf("\t'%svinum routes%s'\n", KYELLOW, KNORMAL);

  printf("\n\n");
}


// This function will display the routes of the application
void display_routes() {
  FILE *fp = fopen(EXECLOC, "r");
  char c;

  if (fp == NULL) {
    print_vinum();
    printf("%sERROR: %sCould not find the routes file. Make sure it's in the /config folder.\n\n", KRED, KNORMAL);
    return;
  }

  while (c = fgetc(fp)) {
    if (c == EOF) {
      return;
    }

    if (c == '{') {

    }
  }
}





// This function will display the version of the system
void display_version() {
  print_vinum();
  printf("Version %s%s\n\n", KRED, VERSION);
}




// This function will display that a feature is not yet implemented
void display_not_available() {
  print_vinum();
  printf("This feature is not implemented yet.\n\n");
}



// This will show the menu of not found
void not_found() {
  printf("\n\n%sERROR: We could not understand what you are trying to do. See the commands below that you can use:%s\n", KRED, KNORMAL);
  display_help_menu();
}



// This function will generate whatever the user wants. We will be apssing the arguments
void generations(int argc, char *argv[]) {
  // If they want to generate a controller
  if (argc == 4 && strcmp(argv[2], "controller") == 0) {
    generate_controller(argv[3]);
    return;
  }


  // If they want to generate a model
  if (argc == 4 && strcmp(argv[2], "model") == 0) {
    generate_model(argv[3]);
    return;
  }

  // If they want to generate a resource
  if (argc > 3 && strcmp(argv[2], "resource") == 0) {
    generate_resource(argv[3]);
    return;
  }

  // If they want to generate a migration
  if (argc == 4 && strcmp(argv[2], "migration") == 0) {
    generate_migration(argv[3]);
    return;
  }
}